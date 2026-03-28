<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile',
        'address',
        'city',
        'state',
        'pincode',
        'address_type',
        'delivery_time',
        'open_saturday',
        'open_sunday',
        'payment_method',
        'payment_status',
        'payment_details',
        'order_status',
        'coupon_code',
        'discount_amount',
        'total_amount',
        'refund_status',
        'refund_amount',
        'refund_initiated_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'payment_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
