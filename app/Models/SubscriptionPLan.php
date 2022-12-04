<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPLan extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_plans';
    protected $fillable = [
        'plan',
        'max_memory_size_in_mega',
        'price'
    ];
}
