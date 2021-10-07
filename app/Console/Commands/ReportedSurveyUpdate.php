<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\surveys;
use DB;
use Carbon;

class ReportedSurveyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:reported';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send count of daily reported surveys';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $oReportSurveyCount = DB::connection('mysql_ars_1')->table('assignments')
        ->select(DB::raw('*'))
        ->whereRaw('Date(reported_at) = "'.Carbon::today().'"')->count();
        
        if($oReportSurveyCount){
            $sMessage = $oReportSurveyCount.' surveys has been reported today';
        }else{
            $sMessage = 'No survey reported today';
        }
        
        

        Mail::raw($sMessage, function($message) use($sMessage)
        {
            $message->to('ruchita.s@wingstechsolutions.com')
                    ->subject('Daily Updates of Reported Survey - ('.$sMessage.')');

        });
        
        Mail::raw($sMessage, function($message) use($sMessage)
        {
            $message->to('rhill@kalanlp.com')
                    ->subject('Daily Updates of Reported Survey - ('.$sMessage.')');

        });

        $this->info('Daily Update has been send successfully');
    }
}
