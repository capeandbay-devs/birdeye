<?php

namespace CapeAndBay\BirdEye\Services;

use CapeAndBay\BirdEye\Library\Business;
use CapeAndBay\BirdEye\Library\Customer;

class LibraryService
{
    public function __construct()
    {

    }

    public function retrieve($feature = '', $option = '')
    {
        $results = false;

        switch($feature)
        {
            case 'business':
                $results = new Business($option);
                break;

            case 'customer':
                $results = new Customer($option);
                break;

            /*
            default:
                $results = $this->basicLoadObj($feature);
            */
        }

        return $results;
    }
}
