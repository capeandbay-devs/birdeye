<?php

namespace CapeAndBay\BirdEye\Console\Commands;

class ImportChildBusinessesCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birdeye:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Child Businesses from a Parent Business ID into the DB.';

    public $cron_name = 'Child Account Migration Cron';
    public $cron_log = 'birdeye-business-import-command-log';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function start()
    {

    }
}
