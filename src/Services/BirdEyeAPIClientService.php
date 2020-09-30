<?php

namespace CapeAndBay\BirdEye\Services;

use CapeAndBay\BirdEye\Models\BirdEyeBusinesses;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class BirdEyeAPIClientService
{
    protected $root_url;
    protected $public_url = '/v1';
    protected $parent_business_id;

    public function __construct(string $account = '')
    {
        $this->root_url = config('birdeye.api_url');
        $this->setBusinessId($account);
    }

    public function getBusinessId()
    {
        return $this->parent_business_id;
    }

    public function setBusinessId(string $account = '') : void
    {
        if(empty($account))
        {
            $this->parent_business_id = config('birdeye.deets.parent_business_id');
        }
        else
        {
            if(array_key_exists($account, config('birdeye.accounts')))
            {
                $this->parent_business_id = config("birdeye.accounts.{$account}");
            }
            else
            {
                if(!is_null(BirdEyeBusinesses::whereBusinessId($account)->first()))
                {
                    $this->parent_business_id = $account;
                }
            }
        }

    }

    public function public_url()
    {
        return $this->root_url.$this->public_url;
    }

    public function get($endpoint)
    {
        $results = false;

        $url = $endpoint;

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        $response = Curl::to($url)
            ->withHeaders($headers)
            ->asJson(true)
            ->get();

        if($response)
        {
            Log::info('AnchorCMS Response from '.$url, $response);
            $results = $response;
        }
        else
        {
            Log::info('AnchorCMS Null Response from '.$url);
        }

        return $results;
    }

    public function post($endpoint, $args = [], $headers = [])
    {
        $results = false;

        $url = $endpoint;

        if(!empty($args))
        {
            if(!empty($headers))
            {
                $response = Curl::to($url)
                    ->withHeaders($headers)
                    ->withData($args)
                    ->asJson(true)
                    ->post();
            }
            else
            {
                $headers = [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ];

                $response = Curl::to($url)
                    ->withData($args)
                    ->withHeaders($headers)
                    ->asJson(true)
                    ->post();
            }
        }
        elseif(!empty($headers))
        {
            $response = Curl::to($url)
                ->withHeaders($headers)
                ->asJson(true)
                ->post();
        }
        else
        {
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ];

            $response = Curl::to($url)
                ->withHeaders($headers)
                ->asJson(true)
                ->post();
        }

        if($response)
        {
            Log::info('AnchorCMS Response from '.$url, $response);
            $results = $response;
        }
        else
        {
            Log::info('AnchorCMS Null Response from '.$url);
        }

        if($response)
        {
            Log::info('AnchorCMS Response from '.$url, $response);
            $results = $response;
        }
        else
        {
            Log::info('AnchorCMS Null Response from '.$url);
        }

        return $results;
    }
}
