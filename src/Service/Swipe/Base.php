<?php

declare(strict_types=1);

namespace App\Service\Swipe;

use App\Entity\User;
use App\Entity\Swipe;
use App\Service\BaseService;
use App\Service\RedisService;
use App\Repository\UserRepository;
use App\Repository\SwipeRepository;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'swipe:%s';

     /** @var UserRepository */
     protected $userRepository;

    /** @var SwipeRepository */
    protected $swipeRepository;

    /** @var RedisService */
    protected $redisService;

    public function __construct(
        SwipeRepository $swipeRepository,
        UserRepository $userRepository,
        RedisService $redisService
    ) {
        $this->swipeRepository = $swipeRepository;
        $this->userRepository = $userRepository;
        $this->redisService = $redisService;
    }

    protected static function validateUserId(object $data): int
    {
        if ($data->userId === $data->profileId) {
            throw new \App\Exception\Swipe('You cannot swipe yourself.', 400);
        }   

        if ($data->userId !== $data->decoded->sub) {
            throw new \App\Exception\Swipe('You cannot swipe on behalf of someone else.', 400);
        }   

        return $data->userId;
    }

    protected static function validatePreference(string $preference): string
    {
        if (! in_array($preference, \App\Entity\Swipe::PREFERENCES)) {
            throw new \App\Exception\Swipe('The preference is not valid.', 400);
        }

        return $preference;
    }

    protected function getOneFromCache(int $swipeId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $swipeId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $swipe = $this->redisService->get($key);
        } else {
            $swipe = $this->getOneFromDb($swipeId)->toJson();
            $this->redisService->setex($key, $swipe);
        }

        return $swipe;
    }

    protected function getOneFromDb(int $swipeId): Swipe
    {
        return $this->swipeRepository->checkAndGetSwipe($swipeId);
    }

    protected function saveInCache(int $id, object $swipe): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $id);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $swipe);
    }

    protected function deleteFromCache(int $swipeId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $swipeId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }
}
