<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    const TYPE_DAILY = 'daily';
    const TYPE_MONTHLY = 'monthly';
    const TYPE_YEARLY = 'yearly';

    public static $validTypes = [
        self::TYPE_DAILY,
        self::TYPE_MONTHLY,
        self::TYPE_YEARLY
    ];

    const PAYMENT_TYPE_ONE_TIME = 'one_time';
    const PAYMENT_TYPE_SUBSCRIPTION = 'subscription';

    public static $validPaymentTypes = [
        self::PAYMENT_TYPE_ONE_TIME,
        self::PAYMENT_TYPE_SUBSCRIPTION
    ];

    protected $fillable = [
        'name',
        'price',
        'duration_in_days'
    ];

    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }
}
