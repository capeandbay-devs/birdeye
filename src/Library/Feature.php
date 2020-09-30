<?php

namespace CapeAndBay\BirdEye\Library;

use CapeAndBay\BirdEye\Services\BirdEyeAPIClientService;

class Feature
{
    public $birdeye_client;

    public function __construct()
    {
        $this->birdeye_client = new BirdEyeAPIClientService();
    }

    /**
     * Returns all whatever from the BirdEye API
     * @return array
     */
    public function get()
    {
        $results = [];

        // Leave it for a child to use, right?

        return $results;
    }

    public function clients_uri()
    {
        return '/client/'.config('shipyard.deets.client_uuid');
    }

    public function setIfExists($key, $arr = [])
    {
        $results = '';

        if(array_key_exists($key, $arr))
        {
            $results = $arr[$key];
        }

        return $results;
    }
}
