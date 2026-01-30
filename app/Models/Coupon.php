<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'expires_at', 'is_active'
    ];

    public $timestamps = false;

    public function isValid()
    {
        return $this->is_active && (!$this->expires_at || $this->expires_at > now());
    }
}
