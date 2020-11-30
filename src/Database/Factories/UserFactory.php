<?php

namespace App\Database\Factories;

use Faker\Factory;
use App\Entity\User;

final class UserFactory
{

    public function __invoke()
    {
        // Todo - refactor to factory class
        $faker = Factory::create();
        $genders = User::GENDERS;
        $gender = $faker->randomElement($genders);

        return [
            'email' => $faker->email,
            'password' => $faker->password,
            'name' => $faker->name($gender),
            'gender' => $gender,
            'dateOfBirth' => $faker->dateTimeBetween('-100 years', '-18 years')->format('Y-m-d'),
            'lat' => $faker->latitude,
            'lng' => $faker->longitude
        ];
    }
}
