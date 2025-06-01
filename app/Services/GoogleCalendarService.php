<?php
namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected $client;
    protected $calendarService;

    public function __construct()
    {
        $this->client = new Google_Client();

        // this line will tell google who you are
        $this->client->setAuthConfig(storage_path('app/google_calendar/service_account.json'));

        // this line will tell google what you want to do
        $this->client->addScope(Google_Service_Calendar::CALENDAR);

        // this line will tell google about account owner
        // $this->client->setSubject(config('services.google_calendar.email'));
        $this->client->setScopes(['https://www.googleapis.com/auth/calendar']);

        info(config('services.google_calendar.email'));
        info(config('services.google_calendar.calendar_ID'));

        $this->calendarService = new Google_Service_Calendar($this->client);
    }

    public function createEvent($eventData)
    {
        info('Creating event in Google Calendar');
        $event = new Google_Service_Calendar_Event([
            'summary' => $eventData['summary'],
            'location' => $eventData['location'],
            'description' => $eventData['description'],
            'start' => [
                'dateTime' => $eventData['start_date_time'],
                'timeZone' => 'Asia/Kolkata',
            ],
            'end' => [
                'dateTime' => $eventData['end_date_time'],
                'timeZone' => 'Asia/Kolkata',
            ],
            // // attendees need to be an array of email addresses
            // 'attendees' => [
            //     [
            //         'email' => $eventData['attendee_email'],
            //         'displayName' => $eventData['attendee_name'],
            //     ],
            // ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 24 * 60], // 1 day before
                    ['method' => 'popup', 'minutes' => 10], // 10 minutes before
                ],
            ],
        ]);

        info('Event data to be sent to Google Calendar');
        info('-----------------------------start here-----------------------------------------------'); 
        Log::info(json_encode($event));

        return $this->calendarService->events->insert(config('services.google_calendar.calendar_ID'), $event);
    }
}

?>