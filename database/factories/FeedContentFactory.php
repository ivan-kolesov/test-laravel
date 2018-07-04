<?php declare(strict_types = 1);

use Faker\Generator as Faker;

$factory->define(App\Models\FeedContent::class, function (Faker $faker) {
    return [
        'feed_id' => 1,
        'title' => $faker->text(100),
        'description' => $faker->text(255),
        'content' => $faker->text(1000),
        'permalink' => $faker->url,
        'read' => $faker->boolean,
        'created_at' => $faker->dateTime,
    ];
});