<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city',
        'district',
        'address',
        'zip_code',
        'contact_name',
        'contact_phone',
        'last_used_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_used_at',
    ];

    public function getFullAddressAttribute()
    {
        return "{$this->zip_code} {$this->city}{$this->district}{$this->address}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
