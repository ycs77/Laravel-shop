<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = factory(\App\Models\Product::class, 30)->create();
        foreach ($products as $product) {
            $attrs = factory(App\Models\ProductSkuAttribute::class, 3)->create(['product_id' => $product->id]);
            foreach ($attrs as $attr) {
                $skus = factory(App\Models\ProductSku::class, 3)->create([
                    'product_id' => $product->id,
                    'attribute_id' => $attr->id,
                ]);
            }
            $product->update(['price' => $skus->min('price')]);
        }
    }
}
