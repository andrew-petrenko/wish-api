<?php

/** @var Factory $factory */

use App\Wish;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Wish::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'user_id' => $faker->uuid,
        'title' => $faker->word,
        'goal_amount' => $faker->numberBetween(10, 150000),
        'deposited_amount' => $faker->numberBetween(0, 100000),
        'description' => $faker->sentence,
        'due_date' => $faker->dateTimeBetween('now', '+ 1 year')->format('Y-m-d'),
        'created_at' => $faker->dateTime->format('Y-m-d H:i:s'),
        'updated_at' => $faker->dateTime->format('Y-m-d H:i:s')
    ];
});
