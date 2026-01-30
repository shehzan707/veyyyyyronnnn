<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile',
        'phone',
        'address',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'pincode',
        'postal_code',
        'country',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
