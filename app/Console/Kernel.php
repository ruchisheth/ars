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
        // Commands\Inspire::class,
        Commands\ReportedSurveyUpdate::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('survey:reported')
                ->dailyAt(config('constants.REPORTSENDTIME'));
        
        $schedule->call('\App\Http\Controllers\Admin\ExportController@exportAllUnExportedSurvey')
                 ->hourly();
                // ->everyFiveMinutes();
                // ->everyTenMinutes();
                
    }
}
