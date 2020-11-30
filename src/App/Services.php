<?php

declare(strict_types=1);

use App\Service\Swipe;
use App\Service\User;
use App\Service\Image;
use Psr\Container\ContainerInterface;

$container['create_user_service'] = static function (
    ContainerInterface $container
): User\Create {
    return new User\Create(
        $container->get('user_repository'),
        $container->get('image_repository'),
        $container->get('redis_service')
    );
};

$container['login_user_service'] = static function (
    ContainerInterface $container
): User\Login {
    return new User\Login(
        $container->get('user_repository'),
        $container->get('image_repository'),
        $container->get('redis_service')
    );
};

$container['find_profiles_service'] = static function (
    ContainerInterface $container
): User\Profiles {
    return new User\Profiles(
        $container->get('user_repository'),
        $container->get('image_repository'),
        $container->get('redis_service')
    );
};

$container['create_swipe_service'] = static function (
    ContainerInterface $container
): Swipe\Create {
    return new Swipe\Create(
        $container->get('swipe_repository'),
        $container->get('user_repository'),
        $container->get('redis_service')
    );
};

$container['create_image_service'] = static function (
    ContainerInterface $container
): Image\Create {
    return new Image\Create(
        $container->get('image_repository'),
        $container->get('user_repository'),
        $container->get('redis_service')    
    );
};
