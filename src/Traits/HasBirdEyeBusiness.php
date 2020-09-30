<?php

namespace CapeAndBay\BirdEye\Traits;

trait HasBirdEyeBusiness
{
    public function birdeye_business()
    {
        return $this->hasOne('CapeAndBay\BirdEye\Models\BirdEyeBusinesses', 'internal_id', $this->birdeye_id_column);
    }
}
