<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ProductSku::class, function (Faker $faker) {
    return [
        'price' => $faker->randomNumber(4),
        'stock' => $faker->randomNumber(5),
        'attr_items_index' => [],
    ];
});
