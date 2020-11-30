<?php

declare(strict_types=1);

namespace App\Controller\Swipe;

use App\Controller\BaseController;
use App\Service\Swipe\Create;

abstract class Base extends BaseController
{
    protected function getCreateSwipeService(): Create
    {
        return $this->container->get('create_swipe_service');
    }
}
