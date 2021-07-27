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
        Commands\DeleteOldDeposits::class,
        Commands\GenerateReferalCode::class,
        Commands\CloseScheduledMatch::class,
        Commands\GetMatchReportMissingDetails::class,
        Commands\SendDailyReports::class,
        Commands\CopyToFeeTable::class,
        Commands\MissingReport::class,
        Commands\FixOngoingRatio::class,
        Commands\BetBot::class,
        Commands\BetBotOddsUpdated::class,
        Commands\SetAndCacheTeamRatios::class,
        Commands\CreateCreditLogDB::class
    ];  

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $serverType = env('SERVER_TYPE', 'database');

        if($serverType == 'database'){

            $schedule->command('deposit:delete')->dailyAt('06:30')
                ->appendOutputTo(storage_path() . '/logs/deposits.log');
        
            // Disable backup temporarily due to double bet issue
            // $schedule->command('backup:run --only-db')->dailyAt("07:00")
            //         ->appendOutputTo(storage_path() . '/logs/backups.log');
            
            // Automatically close scheduled matches
            // $schedule->command('hermes:close-matches')->everyMinute()
            //         ->appendOutputTo(storage_path() . '/logs/schedule-matches.log');
            
            // Search and fix null match ratio
            // $schedule->command('hermes:fixongoingratio')->everyMinute()
            //         ->appendOutputTo(storage_path() . '/logs/fix-match-ratio.log');
            
            // Automatically send daily reports
            $schedule->command('hermes:daily-reports')->daily()
                    ->appendOutputTo(storage_path() . '/logs/daily-reports.log');


            // //cron job for updating bets of bot
            // $schedule->command('betbot:manipulate')->everyMinute()
            //         ->appendOutputTo(storage_path() . '/logs/betbot-adjustments.log');        

            // //cron job for updating bets of bot
            // $schedule->command('betbot:process-updated-odds')->everyMinute()
            //         ->appendOutputTo(storage_path() . '/logs/betbot-updated-match-bet-adjustments.log');   

        }else{

            
            // setting and caching of team ratios for both tournament and matches
            $schedule->command('hermes:set-cache-team-ratios')->everyMinute()->withoutOverlapping();     

        }


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
