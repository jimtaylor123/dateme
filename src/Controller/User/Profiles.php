<?php

declare(strict_types=1);

namespace App\Controller\User;

use Slim\Http\Request;
use Slim\Http\Response;

final class Profiles extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $user = $this->getAuthenticatedUser($request);
        $profiles = $this->getFindProfilesService()->getProfilesFor($user, $request);
        return $this->jsonResponse($response, 'success', $profiles, 200);
    }
}
