<?php

namespace CapeAndBay\BirdEye\Library;

class Business extends Feature
{
    protected $url = '/business';

    public function __construct($account = '')
    {
        parent::__construct();
        $this->birdeye_client->setBusinessId($account);
    }

    public function business_url()
    {
        return $this->birdeye_client->public_url().$this->url;
    }

    public function child_businesses() : array
    {
        $results = [];

        $api_key = config('birdeye.deets.api_key');
        $business_id = $this->birdeye_client->getBusinessId();
        $url = $this->business_url()."/child/all?api_key={$api_key}&pid={$business_id}";

        $response = $this->birdeye_client->get($url);

        if($response && (is_array($response)))
        {
            $results = $response;
        }

        return $results;
    }
}
