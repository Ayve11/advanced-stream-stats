<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'expire_at',
        'user_id',
        'plan_id'
    ];

    protected $dates = [
        'expire_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function subscriptionPlan(){
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
