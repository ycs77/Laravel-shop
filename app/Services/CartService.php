<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return Auth::user()->cartItems()->with(['productSku.product'])->get();
    }

    /**
     * Add product sku to cart.
     *
     * @param  int  $sku_id
     * @param  int  $amount
     * @return \App\Models\CartItem
     */
    public function add($sku_id, $amount)
    {
        $user = Auth::user();
        // 從資料庫中查詢該商品是否已經在購物車中
        if ($item = $user->cartItems()->where('product_sku_id', $sku_id)->first()) {
            // 如果存在則直接疊加商品數量
            $item->update([
                'amount' => $item->amount + $amount,
            ]);
        } else {
            // 否則創建一個新的購物車記錄
            $item = new CartItem(['amount' => $amount]);
            $item->user()->associate($user);
            $item->productSku()->associate($sku_id);
            $item->save();
        }

        return $item;
    }

    /**
     * Remove product sku from cart.
     *
     * @param  int|array  $sku_ids
     * @return void
     */
    public function remove($sku_ids)
    {
        $sku_ids = is_array($sku_ids) ? $sku_ids : func_get_args();

        Auth::user()->cartItems()->whereIn('product_sku_id', $sku_ids)->delete();
    }
}
