<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->userName,
        'display_name' => $faker->name,
        'description' => $faker->text,
        'permissions' => ['foo' => true, 'bar' => false],
    ];
});

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
    ];
});

$factory->define(App\Models\Category::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->text,
    ];
});

$factory->define(App\Models\DummyCategory::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->text,
    ];
});

$factory->define(App\Models\Post::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->text,
        'status' => 'publish',
    ];
});
