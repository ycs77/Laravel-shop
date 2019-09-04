<?php

use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductSkuAttribute;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = factory(Product::class, 20)->create();

        foreach ($products as $product) {
            /** @var \Illuminate\Support\Collection $attrs */
            $attrs = factory(ProductSkuAttribute::class, 3)->create([
                'product_id' => $product->id,
            ]);

            /** @var \App\Models\ProductSkuAttribute $firstAttr */
            $firstAttr = $attrs->pull(0);
            $firstAttrItemsIndexs = collect($firstAttr->items)->keys();
            $otherAttrsItemsIndexs = $attrs->map(function ($attr) {
                return array_keys($attr->items);
            });
            $matrixAttrsItemsIndexs = $firstAttrItemsIndexs->crossJoin(...$otherAttrsItemsIndexs)->all();

            foreach ($matrixAttrsItemsIndexs as $attrItemsIndexs) {
                factory(ProductSku::class)->create([
                    'attr_items_index' => json_encode($attrItemsIndexs),
                    'product_id' => $product->id,
                ]);
            }

            $product->refresh();
            $product->update(['price' => $product->skus->min('price')]);
        }
    }
}
