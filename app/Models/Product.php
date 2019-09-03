<?php

namespace App\Models;

use App\Models\ProductSku;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'image', 'on_sale',
        'rating', 'sold_count', 'review_count', 'price',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'on_sale' => 'boolean',
        'rating' => 'integer',
        'sold_count' => 'integer',
        'review_count' => 'integer',
        'price' => 'integer',
    ];

    /**
     * Get attribute image_url
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        // 如果 image 字段本身就已經是完整的 url 就直接返回
        if (url()->isValidUrl($this->attributes['image'])) {
            return $this->attributes['image'];
        }

        return Storage::disk('public')->url($this->attributes['image']);
    }

    public function attrs()
    {
        return $this->hasMany(ProductSkuAttribute::class);
    }

    public function skus()
    {
        return $this->hasMany(ProductSku::class);
    }
}
