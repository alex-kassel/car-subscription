<?php

namespace CarSubscription\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shared\Models\BaseModel;

class Vehicle extends BaseModel
{
    use SoftDeletes;

    protected $connection = 'cars';
    protected $table = 'cs_vehicles';

    public function casts(): array
    {
        return [
            'consumption' => 'float',
            'additional_kilometer_cost' => 'float',
        ];
    }

    public function plans(): hasMany
    {
        return $this->hasMany(PaymentPlan::class, 'vehicle_id', 'id');
    }
}
