<?php

namespace App\Presenters;

use App\Models\Product;

class ProductPresenter
{
    /**
     * Get product skus output array format.
     *
     * @param  \App\Models\Product  $product
     * @return array|\Illuminate\Support\Collection
     */
    public function skus(Product $product)
    {
        if (!is_null(old('skus'))) {
            return array_values(old('skus'));
        }

        return $product->skus;
    }
}
