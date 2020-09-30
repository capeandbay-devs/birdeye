<?php

namespace CapeAndBay\BirdEye\Console\Commands;

use CapeAndBay\BirdEye\Facades\BirdEye;
use CapeAndBay\BirdEye\Library\Business;

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
        // Get the project's accounts or fail.
        $accounts = $this->getAccounts();

        if(count($accounts) > 0)
        {
            // foreach account
            foreach($accounts as $name => $business_id)
            {
                $this->info('Processing '.$name);
                $business_model = BirdEye::get('business', $name);

                // call to BirdEye
                $businesses = $business_model->child_businesses();

                if(count($businesses) > 0)
                {
                    $this->info('BirdEye returned '.count($businesses).' children for '.$name, $businesses);
                    /**
                     * STEPS
                     * 4. Save or Update the business data
                     * 5. Move on.
                     */
                }
                else
                {
                    $this->info($name.' Does not have any child businesses. Moving on...');
                }
            }
        }
        else
        {
            $this->info('No accounts available. Ending.');
        }
    }

    private function getAccounts() : array
    {
        $results = [];

        // Check the config,
        $single_id = config('birdeye.deets.parent_business_id');

        if((is_null($single_id)) || (empty($single_id)))
        {
            //if deets.parent_business_id is empty, use accounts.
            $multiple_ids = config('birdeye.accounts');
            if(count($multiple_ids) > 0)
            {
                $this->info('Located multiple parents - ', $multiple_ids);
                $results = $multiple_ids;
            }
            else
            {
                $this->info('No accounts found in single or doubles spot.');
            }
        }
        else
        {
            // if deets.parent_business_id is populated, use that
            $this->info('Located single parent - '.$single_id);
            $results['default_parent'] = $single_id;
        }

        return $results;
    }
}
