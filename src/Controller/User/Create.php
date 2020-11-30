<?php

declare(strict_types=1);

namespace App\Controller\User;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Database\Factories\UserFactory;

final class Create extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $user = $this->getCreateUserService()->create( (new UserFactory())() );
        return $this->jsonResponse($response, 'success', $user, 201);
    }
}
