<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\ActivityCalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Google_Client;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        try {
            $user = $request->user();
            
            if (empty($user->google_token)) {
                return response()->json([
                    'message' => 'Tidak ada akses ke Google Calendar',
                    'error' => 'Token Google tidak tersedia'
                ], 400);
            }
            
            // Inisialisasi Google Client
            $client = new Google_Client();
            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            
            // Perbaikan format token
            // Cek apakah token sudah dalam format JSON
            $tokenData = $user->google_token;
            if (!is_array($tokenData)) {
                // Coba parse jika string JSON
                if (is_string($tokenData) && json_decode($tokenData) !== null && json_last_error() === JSON_ERROR_NONE) {
                    $tokenData = json_decode($tokenData, true);
                } else {
                    // Jika bukan JSON valid, buat format JSON yang benar
                    $tokenData = [
                        'access_token' => $user->google_token,
                        'token_type' => 'Bearer',
                        'expires_in' => 3600, // Default 1 jam
                        'created' => time()
                    ];
                    
                    // Update token di database dengan format yang benar
                    $user->update([
                        'google_token' => json_encode($tokenData)
                    ]);
                }
            }
            
            $client->setAccessToken($tokenData);
            
            // Jika token kedaluwarsa, refresh token
            if ($client->isAccessTokenExpired() && !empty($user->google_refresh_token)) {
                Log::info('Token kedaluwarsa, mencoba refresh token');
                try {
                    $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                    $tokens = $client->getAccessToken();
                    
                    // Update token di database
                    $user->update([
                        'google_token' => json_encode($tokens)
                    ]);
                    
                    Log::info('Token berhasil diperbarui');
                } catch (Exception $refreshError) {
                    Log::error('Gagal refresh token: ' . $refreshError->getMessage());
                    
                    // Jika refresh token gagal, mungkin perlu login ulang
                    return response()->json([
                        'message' => 'Sesi Google telah kedaluwarsa, silakan login ulang',
                        'error' => 'Token kedaluwarsa dan tidak dapat diperbarui',
                        'need_relogin' => true
                    ], 401);
                }
            } else if ($client->isAccessTokenExpired()) {
                // Jika token kedaluwarsa dan tidak ada refresh token
                return response()->json([
                    'message' => 'Sesi Google telah kedaluwarsa, silakan login ulang',
                    'error' => 'Token kedaluwarsa dan tidak ada refresh token',
                    'need_relogin' => true
                ], 401);
            }
            
            // Inisialisasi layanan Google Calendar
            $service = new Calendar($client);
            
            // Parameter untuk filter
            $optParams = [
                'maxResults' => 100,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            ];
            
            // Email yang akan difilter (default atau dari request)
            $filterEmail = $request->input('email', 'oc.neotelemetri@gmail.com');
            
            // Ambil events dari Google Calendar
            $calendarId = 'primary'; // Atau ID kalender spesifik
            $results = $service->events->listEvents($calendarId, $optParams);
            $events = [];
            
            foreach ($results->getItems() as $event) {
                $creatorEmail = $event->getCreator() ? $event->getCreator()->getEmail() : null;
                $organizerEmail = $event->getOrganizer() ? $event->getOrganizer()->getEmail() : null;
                
                // Filter hanya event yang creator atau organizer-nya adalah email yang ditentukan
                if ($creatorEmail === $filterEmail || $organizerEmail === $filterEmail) {
                    // Format response sesuai dengan model Kotlin
                    $eventItem = [
                        'summary' => $event->getSummary(),
                        'reminders' => [
                            'useDefault' => $event->getReminders() ? $event->getReminders()->getUseDefault() : true
                        ],
                        'creator' => [
                            'self' => $event->getCreator() ? $event->getCreator()->getSelf() : false,
                            'email' => $creatorEmail
                        ],
                        'kind' => $event->getKind(),
                        'htmlLink' => $event->getHtmlLink(),
                        'created' => $event->getCreated(),
                        'iCalUID' => $event->getICalUID(),
                        'start' => [
                            'dateTime' => $event->getStart()->getDateTime() ?? $event->getStart()->getDate(),
                            'timeZone' => $event->getStart()->getTimeZone()
                        ],
                        'description' => $event->getDescription() ?? '',
                        'eventType' => $event->getEventType() ?? 'default',
                        'sequence' => $event->getSequence(),
                        'organizer' => [
                            'self' => $event->getOrganizer() ? $event->getOrganizer()->getSelf() : false,
                            'email' => $organizerEmail
                        ],
                        'etag' => $event->getEtag(),
                        'location' => $event->getLocation() ?? '',
                        'end' => [
                            'dateTime' => $event->getEnd()->getDateTime() ?? $event->getEnd()->getDate(),
                            'timeZone' => $event->getEnd()->getTimeZone()
                        ],
                        'id' => $event->getId(),
                        'updated' => $event->getUpdated(),
                        'status' => $event->getStatus()
                    ];
                    
                    $events[] = $eventItem;
                }
            }
            
            return response()->json([
                'success' => true,
                'events' => $events
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kalender',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Sync activities with Google Calendar
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncWithGoogle(Request $request)
    {
        try {
            $user = $request->user();
            
            if (empty($user->google_token)) {
                return response()->json([
                    'message' => 'Tidak ada akses ke Google Calendar',
                    'error' => 'Token Google tidak tersedia'
                ], 400);
            }
            
            // Validasi input
            $validator = Validator::make($request->all(), [
                'email' => 'nullable|email',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Inisialisasi Google Client
            $client = new Google_Client();
            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            $client->setAccessToken($user->google_token);
            
            // Jika token kedaluwarsa, refresh token
            if ($client->isAccessTokenExpired() && !empty($user->google_refresh_token)) {
                $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                $tokens = $client->getAccessToken();
                
                // Update token di database
                $user->update([
                    'google_token' => json_encode($tokens)
                ]);
            }
            
            // Inisialisasi layanan Google Calendar
            $service = new Calendar($client);
            
            // Parameter untuk filter email tertentu
            $optParams = [
                'maxResults' => 100,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            ];
            
            // Jika ada parameter email untuk filter
            if ($request->has('email')) {
                $optParams['sharedExtendedProperty'] = 'creatorEmail=' . $request->email;
            }
            
            // Ambil events dari Google Calendar
            $calendarId = 'primary'; // Atau ID kalender spesifik
            $results = $service->events->listEvents($calendarId, $optParams);
            
            $syncedEvents = 0;
            
            foreach ($results->getItems() as $event) {
                // Filter berdasarkan creator email jika parameter email ada
                if ($request->has('email')) {
                    $creator = $event->getCreator();
                    if ($creator && $creator->getEmail() !== $request->email) {
                        continue; // Lewati event yang bukan dari email yang diminta
                    }
                }
                
                // Cek apakah event sudah ada di database
                $existingEvent = ActivityCalendarEvent::where('google_event_id', $event->getId())
                    ->where('user_id', $user->id)
                    ->first();
                
                if (!$existingEvent) {
                    // Buat event baru di database
                    ActivityCalendarEvent::create([
                        'user_id' => $user->id,
                        'google_event_id' => $event->getId(),
                        'synced_at' => now(),
                    ]);
                    
                    $syncedEvents++;
                }
            }
            
            return response()->json([
                'message' => 'Sinkronisasi dengan Google Calendar berhasil',
                'synced_events' => $syncedEvents
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal sinkronisasi dengan Google Calendar',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}