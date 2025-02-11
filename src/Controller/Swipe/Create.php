<?php

declare(strict_types=1);

namespace App\Controller\Swipe;

use Slim\Http\Request;
use Slim\Http\Response;

final class Create extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $input = (array) $request->getParsedBody();
        $result = $this->getCreateSwipeService()->create($input);
        return $this->jsonResponse($response, 'success', $result, 201);
    }
}
