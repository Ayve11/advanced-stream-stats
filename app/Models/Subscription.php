<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_ONE_TIME = 'one_time';

    public static $validStatuses = [
        self::STATUS_ACTIVE,
        self::STATUS_EXPIRED,
        self::STATUS_CANCELLED,
        self::STATUS_ONE_TIME
    ];

    protected $fillable = [
        'expired_at',
        'user_id',
        'subscription_plan_id',
        'braintree_subscription_id'
    ];

    protected $dates = [
        'expired_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan(){
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function getStatusDescriptionAttribute(){
        switch($this->status){
            case self::STATUS_ACTIVE:
                return "Payment will be repeated after current subscription ends";
            case self::STATUS_ONE_TIME:
            case self::STATUS_CANCELLED:
                return "Subscription will end with the current expiration time.";
        }
        return "";
    }

    public function isActive(){
        return now()->lt($this->expired_at);
    }

    public function getExpiredAtAttribute( $value ) {
        return $this->attributes['expired_at'] = (new Carbon($value))->toDateTimeLocalString();
      }
}
