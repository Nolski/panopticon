<?php

use Faker\Generator as Faker;

$factory->define(Spatie\Permission\Models\Role::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'guard_name' => 'web'
    ];
});