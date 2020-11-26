<?php

declare(strict_types=1);

namespace App\Controller\Swipe;

use App\Controller\BaseController;
use App\Service\Swipe\Create;
use App\Service\Swipe\Find;

abstract class Base extends BaseController
{
    protected function getCreateSwipeService(): Create
    {
        return $this->container->get('create_swipe_service');
    }

    protected function getFindSwipeService(): Find
    {
        return $this->container->get('find_swipe_service');
    }
}
