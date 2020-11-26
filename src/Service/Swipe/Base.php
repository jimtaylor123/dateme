<?php

declare(strict_types=1);

namespace App\Service\Swipe;

use App\Entity\Swipe;
use App\Repository\SwipeRepository;
use App\Service\BaseService;
use App\Service\RedisService;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'swipe:%s';

    /** @var SwipeRepository */
    protected $swipeRepository;

    /** @var RedisService */
    protected $redisService;

    public function __construct(
        SwipeRepository $swipeRepository,
        RedisService $redisService
    ) {
        $this->swipeRepository = $swipeRepository;
        $this->redisService = $redisService;
    }

    protected static function validateUserId(int $userId): int
    {
        // if (! v::length(1, 50)->validate($name)) {
        //     throw new \App\Exception\Swipe('The name of the swipe is invalid.', 400);
        // }

        return $userId;
    }

    protected static function validateProfileId(int $profileId): int
    {
        // if (! v::length(1, 50)->validate($name)) {
        //     throw new \App\Exception\Swipe('The name of the swipe is invalid.', 400);
        // }

        return $profileId;
    }

    protected static function validatePreference(string $preference): string
    {
        // if (! v::length(1, 50)->validate($name)) {
        //     throw new \App\Exception\Swipe('The name of the swipe is invalid.', 400);
        // }

        return $preference;
    }


        // check logged in user is swiping user
        // check user is not swiping self
        // check swiping user exists

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
