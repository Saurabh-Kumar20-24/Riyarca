<?php

namespace App\Console\Commands;

use App\Models\EmployeeNotification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendBirthdayNotifications extends Command
{
    protected $signature   = 'notifications:birthdays';
    protected $description = 'Fire birthday notifications for employees whose birthday is today';

    public function handle(): void
    {
        $today = now()->format('m-d');

        $birthdayEmployees = User::whereRaw("DATE_FORMAT(dob, '%m-%d') = ?", [$today])->get();

        foreach ($birthdayEmployees as $employee) {
            // Skip if already sent today
            $alreadySent = EmployeeNotification::where('type', 'birthday')
                ->where('triggered_by', $employee->id)
                ->whereDate('created_at', today())
                ->exists();

            if ($alreadySent) {
                continue;
            }

            EmployeeNotification::broadcast(
                type:        'birthday',
                title:       "🎂 It's {$employee->name}'s Birthday!",
                message:     "Today is {$employee->name}'s birthday! Wish them a wonderful day. 🎉",
                triggeredBy: $employee->id,
                meta:        ['age' => Carbon::parse($employee->dob)->age],
            );

            $this->info("Birthday notification sent for {$employee->name}");
        }

        $this->info('Birthday check complete.');
    }
}