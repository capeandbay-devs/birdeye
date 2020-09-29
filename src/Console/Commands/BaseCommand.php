<?php

namespace CapeAndBay\BirdEye\Console\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    public $start;
    public $cron_name = 'Base Command';
    public $cron_log = 'base-command-log';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->start = microtime(true);
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Now running {$this->cron_name}");

        $this->start();

        $this->info('fin.');
        $this->trackTheTime($this->start);
    }

    public function start()
    {
        // This should be handled by the child. Just here for shits and giggles.
        $this->info('Derp');
    }

    public function logTheDate(array $props)
    {
        $good_date = $this->getTheGoodDate();

        //Log the activity
        $new_props = array_merge($props, [
            'time_executed' => $good_date,
        ]);
        activity($this->cron_log)
            ->withProperties($new_props)
            ->log("{$this->cron_name} executed.");
    }

    public function trackTheTime($start)
    {
        $time_elapsed_secs = microtime(true) - $start;
        $duration = $time_elapsed_secs;
        $hours = (int)($duration/60/60);
        $minutes = (int)($duration/60)-$hours*60;
        $seconds = (int)$duration-$hours*60*60-$minutes*60;
        $this->info('Time to finish - '."{$hours}:{$minutes}:{$seconds}\n");
    }

    public function getTheGoodDate()
    {
        $date = new \DateTime(date('Y-m-d h:i:s'));
        $date->setTimezone(new \DateTimeZone('America/New_York'));
        return $date->format('h:i A');
    }

    function progress_bar($done, $total, $info="", $width=50) {
        $perc = round(($done * 100) / $total);
        $bar = round(($width * $perc) / 100);
        $this->info(sprintf("%s%%[%s>%s]%s\r", $perc, str_repeat("=", $bar), str_repeat(" ", $width-$bar), $info));
        system('clear');

    }
}
