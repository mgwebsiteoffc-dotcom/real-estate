<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected $client;
    protected $calendar;

    public function __construct($credentials)
    {
        $this->client = new Client();
        $this->client->setApplicationName('Real Estate CRM');
        $this->client->setScopes([Calendar::CALENDAR]);
        $this->client->setAuthConfig(json_decode($credentials, true));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        $this->calendar = new Calendar($this->client);
    }

    public function createEvent($appointment)
    {
        try {
            $event = new \Google\Service\Calendar\Event([
                'summary' => $appointment->title,
                'description' => $appointment->description,
                'location' => $appointment->location,
                'start' => [
                    'dateTime' => $appointment->start_time->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ],
                'end' => [
                    'dateTime' => $appointment->end_time->toRfc3339String(),
                    'timeZone' => config('app.timezone'),
                ],
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 24 * 60],
                        ['method' => 'popup', 'minutes' => 30],
                    ],
                ],
            ]);

            $calendarId = 'primary';
            $event = $this->calendar->events->insert($calendarId, $event);

            return $event->getId();
        } catch (\Exception $e) {
            Log::error('Google Calendar Error: ' . $e->getMessage());
            return null;
        }
    }

    public function updateEvent($googleEventId, $appointment)
    {
        try {
            $event = $this->calendar->events->get('primary', $googleEventId);

            $event->setSummary($appointment->title);
            $event->setDescription($appointment->description);
            $event->setLocation($appointment->location);
            $event->setStart(new \Google\Service\Calendar\EventDateTime([
                'dateTime' => $appointment->start_time->toRfc3339String(),
                'timeZone' => config('app.timezone'),
            ]));
            $event->setEnd(new \Google\Service\Calendar\EventDateTime([
                'dateTime' => $appointment->end_time->toRfc3339String(),
                'timeZone' => config('app.timezone'),
            ]));

            $this->calendar->events->update('primary', $googleEventId, $event);

            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar Update Error: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteEvent($googleEventId)
    {
        try {
            $this->calendar->events->delete('primary', $googleEventId);
            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar Delete Error: ' . $e->getMessage());
            return false;
        }
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function authenticate($code)
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        return $token;
    }
}
