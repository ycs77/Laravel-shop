<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ProductRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'image' => 'required|image',
            'description' => 'required',
            'attrs' => 'array',
            'attrs.*.name' => 'required|string',
            'attrs.*.items' => 'required|array',
            'attrs.*.items.*' => 'required|string',
            'skus' => 'array',
            'skus.*.price' => 'required|integer',
            'skus.*.stock' => 'required|integer',
            'skus.*.attr_items_index' => 'required|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => '商品名稱',
            'image' => '封面圖片',
            'description' => '商品介紹',
            'on_sale' => '是否上架',
            'price' => '價格',
            'attrs' => 'SKU 欄位',
            'attrs.*.name' => 'SKU 欄位名稱',
            'attrs.*.items' => 'SKU 欄位選項',
            'attrs.*.items.*' => 'SKU 欄位選項',
            'skus' => 'SKU',
            'skus.*.price' => 'SKU 單價',
            'skus.*.stock' => 'SKU 庫存',
            'skus.*.attr_items_index' => 'SKU 欄位選項 ID',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'attrs.*.items.required' => '請新增 SKU 欄位選項',
        ];
    }
}
