<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ProductSkuAttribute::class, function (Faker $faker) {
    $attrs_data = [
        '顏色' => ['紅', '黃', '藍'],
        '大小' => ['大', '中', '小'],
        '容量' => ['100cc', '500cc', '1000cc'],
        '尺寸' => ['100cm', '500cm', '1000cm'],
    ];

    $attr_name = $faker->randomElement(array_keys($attrs_data));

    return [
        'name' => $attr_name,
        'values' => $attrs_data[$attr_name],
    ];
});
