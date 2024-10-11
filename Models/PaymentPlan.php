<?php

namespace CarSubscription\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentPlan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'cars';
    protected $table = 'cs_plans';

    public function casts(): array
    {
        return [
            'price' => 'float',
            'additional_kilometer_cost' => 'float',
        ];
    }
}
