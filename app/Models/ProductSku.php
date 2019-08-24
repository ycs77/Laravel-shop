<?php

namespace App\Models;

use App\Exceptions\InternalException;
use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'price', 'stock', 'attr_items_index',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * Get the product sku's attributes items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAttrsAttribute()
    {
        $attrs = $this->product->attrs;
        $attr_items_index = json_decode($this->attr_items_index, true);
        return collect($attr_items_index)->mapWithKeys(function ($item_index, $index) use ($attrs) {
            $attr = $attrs->get($index);
            return [$attr->name => $attr->items[$item_index]];
        });
    }

    /**
     * Decrease product sku stock.
     *
     * @param  int  $amount
     * @return int
     */
    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('減少的庫存量不可小於0');
        }

        return $this->query()
            ->where('id', $this->id)
            ->where('stock', '>=', $amount)
            ->decrement('stock', $amount);
    }

    /**
     * Add product sku stock.
     *
     * @param  int  $amount
     * @return int
     */
    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('增加的庫存量不可小於0');
        }

        $this->increment('stock', $amount);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
