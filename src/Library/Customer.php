<?php

namespace CapeAndBay\BirdEye\Library;

use Illuminate\Support\Facades\Validator;

class Customer extends feature
{
    protected $url = '/customer';

    private $customer_id;
    private $details = [];

    public function __construct($account = '')
    {
        parent::__construct();
        $this->birdeye_client->setBusinessId($account);
    }

    public function customer_url()
    {
        return $this->birdeye_client->public_url().$this->url;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    /**
     * Check the EndUser into a Business. Will trigger any listeners on BirdEye's end
     *      such as an email firing to the EndUser. Will return the results to that
     *      the developer may do something with it. Will also populate the data attributes
     *      of the object for serialization or OOP usage of the data.
     *      Will return a string with the error and code if the call failed.
     *      If validation fails, will return an empty array.
     * @param array $payload
     * @return array|mixed|string
     */
    public function check_in(array $payload = [])
    {
        $results = [];

        $validated = Validator::make($payload, [
            'name' => 'bail|required',
            'emailId' => 'bail|required|email:rfc,dns',
            'phone' => 'bail|required|numeric',
            'smsEnabled' => 'bail|required|boolean',
        ]);

        if ($validated->fails())
        {
            foreach($validated->errors()->toArray() as $idx => $error_msg)
            {
                $results = "Error (Local) 500 - {$error_msg[0]}";
            }
        }
        else
        {
            $api_key = config('birdeye.deets.api_key');
            $business_id = $this->birdeye_client->getBusinessId();
            $url = $this->customer_url()."/checkin?api_key={$api_key}&bid={$business_id}";


            $response = $this->birdeye_client->post($url, $payload);

            if($response && (is_array($response)))
            {
                if(array_key_exists('code', $response))
                {
                    $results = "Error {$response['code']} - {$response['message']}";
                }
                elseif(array_key_exists('customerId', $response))
                {
                    $this->setCustomerId($response['customerId']);
                    $this->setCustomerDetails($payload);
                    $results = $response;
                }
            }
        }

        return $results;
    }

    public function setCustomerDetails($details) : void
    {
        $this->details = $details;
    }

    public function getCustomerDetails() : array
    {
        return $this->details;
    }
}
