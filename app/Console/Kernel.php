<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\StarterModelGenerator::class,
        Commands\StarterRouteGenerator::class,
        Commands\StarterRequestGenerator::class,
        Commands\StarterControllerGenerator::class,
        Commands\StarterRepositoryGenerator::class,
        Commands\StarterTransformerGenerator::class,
        Commands\StarterResourceGenerator::class,
        Commands\StarterDestroyGenerator::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
