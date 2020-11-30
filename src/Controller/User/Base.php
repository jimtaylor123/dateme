<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Service\User\Create;
use App\Service\User\Find;
use App\Service\User\Login;
use App\Service\User\Profiles;

abstract class Base extends BaseController
{
    protected function getCreateUserService(): Create
    {
        return $this->container->get('create_user_service');
    }

    protected function getLoginUserService(): Login
    {
        return $this->container->get('login_user_service');
    }

    protected function getFindProfilesService(): Profiles
    {
        return $this->container->get('find_profiles_service');
    }

}
