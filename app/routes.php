<?php

$app->get('/', 'App\Controller\DefaultController:getHelp');
$app->get('/version', 'App\Controller\DefaultController:getVersion');
$app->get('/status', 'App\Controller\DefaultController:getStatus');

$app->group('/api/v1', function () use ($app) {
    $app->group('/tasks', function () use ($app) {
        $app->get('', 'App\Controller\Task\GetAllTasks:getTasks');
        $app->get('/[{id}]', 'App\Controller\Task\TaskController:getTask');
        $app->get('/search/[{query}]', 'App\Controller\Task\TaskController:searchTasks');
        $app->post('', 'App\Controller\Task\TaskController:createTask');
        $app->put('/[{id}]', 'App\Controller\Task\TaskController:updateTask');
        $app->delete('/[{id}]', 'App\Controller\Task\TaskController:deleteTask');
    });
    $app->group('/users', function () use ($app) {
        $app->get('', 'App\Controller\User\UserController:getUsers');
        $app->get('/[{id}]', 'App\Controller\User\UserController:getUser');
        $app->get('/search/[{query}]', 'App\Controller\User\UserController:searchUsers');
        $app->post('', 'App\Controller\User\UserController:createUser');
        $app->put('/[{id}]', 'App\Controller\User\UserController:updateUser');
        $app->delete('/[{id}]', 'App\Controller\User\UserController:deleteUser');
    });
});
