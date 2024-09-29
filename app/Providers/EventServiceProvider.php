<?php

namespace App\Providers;

use App\Models\Appointments;
use App\Models\Doctor;
use App\Models\Patients;
use App\Models\User;
use App\Observers\AppointmentsObserver;
use App\Observers\DoctorObserver;
use App\Observers\PatientsObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Patients::observe(PatientsObserver::class);
        Doctor::observe(DoctorObserver::class);
        User::observe(UserObserver::class);
        Appointments::observe(AppointmentsObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
