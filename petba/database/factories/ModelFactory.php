<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Brackets\AdminAuth\Models\AdminUser::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'activated' => true,
        'forbidden' => $faker->boolean(),
        'language' => 'en',
        'deleted_at' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        'last_login_at' => $faker->dateTime,
        
    ];
});/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Vet::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'image' => $faker->text(),
        'phone_number' => $faker->sentence,
        'address' => $faker->text(),
        'details' => $faker->text(),
        'gender' => $faker->randomNumber(5),
        'latitude' => $faker->randomFloat,
        'longitude' => $faker->randomFloat,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Vet::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'image' => $faker->text(),
        'phone_number' => $faker->sentence,
        'address' => $faker->text(),
        'details' => $faker->text(),
        'gender' => $faker->randomNumber(5),
        'latitude' => $faker->randomFloat,
        'longitude' => $faker->randomFloat,
        'enabled' => $faker->boolean(),
        
        
    ];
});
