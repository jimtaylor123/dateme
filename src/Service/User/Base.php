<?php

declare(strict_types=1);

namespace App\Service\User;

use DateTime;
use App\Entity\User;
use App\Service\BaseService;
use App\Service\RedisService;
use App\Repository\UserRepository;
use Respect\Validation\Validator as v;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'user:%s';

    /** @var UserRepository */
    protected $userRepository;

    /** @var RedisService */
    protected $redisService;

    public function __construct(
        UserRepository $userRepository,
        RedisService $redisService
    ) {
        $this->userRepository = $userRepository;
        $this->redisService = $redisService;
    }

    protected static function validateUserName(string $name): string
    {
        if (! v::alnum('ÁÉÍÓÚÑáéíóúñ.')->length(1, 100)->validate($name)) {
            throw new \App\Exception\User('Invalid name.', 400);
        }

        return $name;
    }

    protected static function validateEmail(string $emailValue): string
    {
        $email = filter_var($emailValue, FILTER_SANITIZE_EMAIL);
        if (! v::email()->validate($email)) {
            throw new \App\Exception\User('Invalid email', 400);
        }

        return (string) $email;
    }

    protected static function validateGender(string $gender): string
    {
        if( ! in_array($gender, \App\Entity\User::GENDERS)){
            throw new \App\Exception\User('Invalid gender - please choose from male or female', 400);
        }

        return (string) $gender;
    }

    protected static function validateDateOfBirth(string $dateOfBirth): DateTime
    {
        if(! $dateOfBirth = DateTime::createFromFormat('Y-m-d', $dateOfBirth)){
            throw new \App\Exception\User("The date of birth must be a valid date formatted as yyyy-mm-dd, e.g. 1970-01-01.", 400);
        } else if ($dateOfBirth > (new DateTime('now'))->modify('-18 years')){
            throw new \App\Exception\User("You must be at least 18 years old to use this application", 400);
        }

        return $dateOfBirth;
    }

    protected function getUserFromCache(int $userId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $userId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $data = $this->redisService->get($key);
            $user = json_decode((string) json_encode($data), false);
        } else {
            $user = $this->getUserFromDb($userId)->toJson();
            $this->redisService->setex($key, $user);
        }

        return $user;
    }

    protected function getUserFromDb(int $userId): User
    {
        return $this->userRepository->getUser($userId);
    }

    protected function saveInCache(int $id, object $user): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $id);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $user);
    }

    protected function deleteFromCache(int $userId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $userId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }
}
