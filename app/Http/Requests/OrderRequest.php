<?php

namespace App\Http\Requests;

use App\Models\ProductSku;
use Illuminate\Validation\Rule;

class OrderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_id'     => ['required', Rule::exists('user_addresses', 'id')->where('user_id', $this->user()->id)],
            'items'          => 'required|array',
            'items.*.sku_id' => [ // 檢查 items 數組下每一個子數組的 sku_id 參數
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        $fail('該商品不存在');
                        return;
                    }
                    if (!$sku->product->on_sale) {
                        $fail('該商品未上架');
                        return;
                    }
                    if ($sku->stock === 0) {
                        $fail('該商品已售完');
                        return;
                    }
                    // 獲取當前索引
                    preg_match('/items\.(\d+)\.sku_id/', $attribute, $m);
                    $index  = $m[1];
                    // 根據索引找到用戶所提交的購買數量
                    $amount = $this->input('items')[$index]['amount'];
                    if ($amount > 0 && $amount > $sku->stock) {
                        return $fail('該商品庫存不足');
                    }
                },
            ],
            'items.*.amount' => 'required|integer|min:1',
        ];
    }
}
