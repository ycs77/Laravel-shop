<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {
    $image = $faker->randomElement([
        asset('images/7kG1HekGK6.jpg'),
        asset('images/1B3n0ATKrn.jpg'),
        asset('images/r3BNRe4zXG.jpg'),
        asset('images/C0bVuKB2nt.jpg'),
        asset('images/82Wf2sg8gM.jpg'),
        asset('images/nIvBAQO5Pj.jpg'),
        asset('images/XrtIwzrxj7.jpg'),
        asset('images/uYEHCJ1oRp.jpg'),
        asset('images/2JMRaFwRpo.jpg'),
        asset('images/pa7DrV43Mw.jpg'),
    ]);

    return [
        'title'        => $faker->word,
        'description'  => $faker->sentence,
        'image'        => $image,
        'on_sale'      => true,
        'rating'       => $faker->numberBetween(0, 5),
        'sold_count'   => 0,
        'review_count' => 0,
        'price'        => 0,
    ];
});
