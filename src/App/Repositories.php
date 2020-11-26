<?php

declare(strict_types=1);

use App\Repository\SwipeRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;

$container['user_repository'] = static function (
    ContainerInterface $container
): UserRepository {
    return new UserRepository($container->get('db'));
};

$container['swipe_repository'] = static function (
    ContainerInterface $container
): SwipeRepository {
    return new SwipeRepository($container->get('db'));
};
