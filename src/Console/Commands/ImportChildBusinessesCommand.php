<?php

namespace CapeAndBay\BirdEye\Console\Commands;

use CapeAndBay\BirdEye\Facades\BirdEye;
use CapeAndBay\BirdEye\Library\Business;
use CapeAndBay\BirdEye\Models\BirdEyeBusinesses;

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

    protected $businesses_model;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BirdEyeBusinesses $businesses)
    {
        $this->start = microtime(true);
        parent::__construct();

        $this->businesses_model = $businesses;
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
            foreach($accounts as $name => $parent_id)
            {
                $this->info('Processing '.$name);
                $business_model = BirdEye::get('business', $name);

                // call to BirdEye
                $businesses = $business_model->child_businesses();

                if(count($businesses) > 0)
                {
                    $this->info('BirdEye returned '.count($businesses).' children for '.$name.'  - '. json_encode($businesses));

                    foreach ($businesses as $business)
                    {
                        //DB Storage
                        if(array_key_exists('createdOn', $business))
                        {
                            $business['be_created_on'] = $business['createdOn'];
                            unset($business['createdOn']);
                        }

                        if(array_key_exists('childCount', $business))
                        {
                            $business['child_count'] = $business['childCount'];
                            unset($business['childCount']);
                        }

                        $business['parent_id'] = $parent_id;
                        $business['business_id'] = $business['id'];

                        if(!($saved_record = $this->businesses_model->findByBusinessId($business['id'])))
                        {
                            // Save the business data
                            unset($business['id']);
                            $saved_record = new $this->businesses_model($business);
                            $saved_record->save();
                        }
                        else
                        {
                            // Update the business data
                            unset($business['id']);
                            $saved_record->update($business);
                        }
                    }
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
                $this->info('Located multiple parents - '. json_encode($multiple_ids));
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
