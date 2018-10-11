<?php

use Faker\Generator as Faker;

$factory->define(App\Models\UserAddress::class, function (Faker $faker) {
    $addresses = [
        ['臺北市', '大安區'],
        ['新北市', '板橋區'],
        ['臺中市', '西屯區'],
        ['雲林縣', '斗南鎮'],
        ['高雄市', '三民區'],
    ];
    $address = $faker->randomElement($addresses);

    $roads = [
        '八德路',
        '民生東路',
        '大墩南路',
        '保安三街',
    ];
    $road = $faker->randomElement($roads);

    return [
        'city'          => $address[0],
        'district'      => $address[1],
        'address'       => sprintf('%s%d號%d樓', $road, rand(1, 100), rand(1, 4)),
        'zip_code'      => $faker->randomNumber(3),
        'contact_name'  => $faker->name,
        'contact_phone' => $faker->phoneNumber,
    ];
});
