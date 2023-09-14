<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\FetchMovies;
use App\Console\Commands\SendMovies;

class Kernel extends ConsoleKernel {
    protected $commands = [
        SendMovies::class
    ];

    protected function schedule(Schedule $schedule) {
        $schedule->job(new FetchMovies)->dailyAt('12:00');
    }

    protected function commands(){
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__.'/Jobs');

        require base_path('routes/console.php');
    }

}
