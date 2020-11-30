<?php

declare(strict_types=1);

use App\Middleware\Auth;
use App\Controller\User;
use App\Controller\Swipe;
use App\Controller\Image;

/** @var \Slim\App $app */

$app->get('/', 'App\Controller\DefaultController:getHelp');
$app->get('/status', 'App\Controller\DefaultController:getStatus');

$app->post('/login', \App\Controller\User\Login::class);

$app->group('/user', function () use ($app): void {
    $app->post('/create', User\Create::class);
    $app->post('/gallery', Image\Create::class)->add(new Auth());
});

$app->get('/profiles', User\Profiles::class)->add(new Auth());
$app->post('/swipe', Swipe\Create::class)->add(new Auth());
