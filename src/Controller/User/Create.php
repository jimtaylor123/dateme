<?php

declare(strict_types=1);

namespace App\Controller\User;

use Faker\Factory;
use Slim\Http\Request;
use Slim\Http\Response;

final class Create extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        // Todo - refactor to factory class
        $faker = Factory::create();
        $genders = \App\Entity\User::GENDERS;
        $gender = $faker->randomElement($genders);

        $input = [
            'email' => $faker->email, 
            'password' => $faker->password, 
            'name' => $faker->name($gender), 
            'gender' => $gender, 
            'dateOfBirth' => $faker->dateTimeBetween('-100 years', '-18 years')->format('Y-m-d')
        ];

        $user = $this->getCreateUserService()->create($input);

        return $this->jsonResponse($response, 'success', $user, 201);
    }
}
