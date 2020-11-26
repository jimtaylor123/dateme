<?php

declare(strict_types=1);

use App\Middleware\Auth;
use App\Controller\User;
use App\Controller\Swipe;

/** @var \Slim\App $app */

$app->get('/', 'App\Controller\DefaultController:getHelp');
$app->post('/login', \App\Controller\User\Login::class);

$app->group('/user', function () use ($app): void {
    $app->post('/create', User\Create::class);
    // $app->post('/gallery', User\Gallery::class);
});

$app->get('/profiles', User\Profiles::class)->add(new Auth());
$app->post('/swipe', Swipe\Create::class)->add(new Auth());
