<?php

namespace CapeAndBay\BirdEye\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BirdEyeBusinesses extends Model
{
    use SoftDeletes, Uuid;

    protected $table = 'birdeye_businesses';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $fillable = [
        'business_id',
        'parent_id',
        'name',
        'alias',
        'address',
        'phone',
        'status',
        'be_created_on',
        'type',
        'child_count',
        'internal_id'
    ];

    protected $casts = [
        'id' => 'uuid',
        'address' => 'array'
    ];

    public function findByBusinessId($bid)
    {
        $results = false;

        if(!is_null($record = $this->whereBusinessId($bid)->first()))
        {
            $results = $record;
        }

        return $results;
    }
}
