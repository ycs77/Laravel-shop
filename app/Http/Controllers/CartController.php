<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Requests\AddCartRequest;

class CartController extends Controller
{
    public function add(AddCartRequest $request)
    {
        $user   = $request->user();
        $skuId  = $request->input('sku_id');
        $amount = $request->input('amount');

        // 從數據庫中查詢該商品是否已經在購物車中
        if ($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()) {
            // 如果存在則直接增加商品數量
            $cart->update([
                'amount' => $cart->amount + $amount,
            ]);
        } else {
            // 否則創建一個新的購物車記錄
            $cart = new CartItem(['amount' => $amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }

        return [];
    }
}
