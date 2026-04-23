<?php

use App\Console\Commands\SendBirthdayNotifications;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote()); 
})->purpose('Display an inspiring quote');

// Scheduled Tasks 
Schedule::command(SendBirthdayNotifications::class)->dailyAt('08:00');