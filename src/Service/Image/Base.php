<?php

declare(strict_types=1);

namespace App\Service\Image;

use App\Entity\User;
use App\Entity\Image;
use Slim\Http\Request;
use App\Service\BaseService;
use App\Service\RedisService;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use Slim\Http\UploadedFile;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'image:%s';

    /** @var ImageRepository */
    protected $imageRepository;

     /** @var UserRepository */
     protected $userRepository;

    /** @var RedisService */
    protected $redisService;

    public function __construct(
        ImageRepository $imageRepository,
        UserRepository $userRepository,
        RedisService $redisService
    ) {
        $this->imageRepository = $imageRepository;
        $this->userRepository = $userRepository;
        $this->redisService = $redisService;
    }

    protected static function createUniqueName(): string
    {
        $name = str_replace(".", "-", uniqid('', true)). ".jpeg";
        return $name;
    }

    protected function getOneFromCache(int $imageId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $imageId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $image = $this->redisService->get($key);
        } else {
            $image = $this->getOneFromDb($imageId)->toJson();
            $this->redisService->setex($key, $image);
        }

        return $image;
    }

    protected function getOneFromDb(int $imageId): Image
    {
        return $this->imageRepository->checkAndGetImage($imageId);
    }

    protected function saveInCache(int $id, object $image): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $id);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $image);
    }

    protected function deleteFromCache(int $imageId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $imageId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }
}
