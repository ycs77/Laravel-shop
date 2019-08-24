<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSkuAttribute extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'items',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'items' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
