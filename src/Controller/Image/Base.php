<?php

declare(strict_types=1);

namespace App\Controller\Image;

use App\Controller\BaseController;
use App\Service\Image\Create;
use App\Service\Image\Find;

abstract class Base extends BaseController
{
    protected function getCreateImageService(): Create
    {
        return $this->container->get('create_image_service');
    }

    protected function getFindImageService(): Find
    {
        return $this->container->get('find_image_service');
    }
}
