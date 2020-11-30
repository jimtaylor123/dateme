<?php

declare(strict_types=1);

namespace App\Controller\Image;

use Slim\Http\Request;
use Slim\Http\Response;

final class Create extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $images = $this->getCreateImageService()->create( $request );
        return $this->jsonResponse($response, 'success', $images, 201);
    }
}
