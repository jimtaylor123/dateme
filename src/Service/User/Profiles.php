<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use Slim\Http\Request;

final class Profiles extends Base
{
    public function getOne(int $userId): object
    {
        if (self::isRedisEnabled() === true) {
            $user = $this->getUserFromCache($userId);
        } else {
            $user = $this->getUserFromDb($userId)->toJson();
        }

        return $user;
    }

    public function getProfiles(Request $request) 
    {
        $profiles = $this->userRepository->getProfiles($request);
        $images = $this->imageRepository->getUserImages($profiles);
        $scheme = $request->getServerParams()['REQUEST_SCHEME'];
        $host = $request->getServerParams()['HTTP_HOST'];
         foreach ($images as $image) {
             $image['url'] = "$scheme://$host/images/{$image['name']}";
             $profiles[$image['userId']]['images'][] = $image;
         }

        return $profiles;
    }
}
