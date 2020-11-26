<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;

final class Profiles extends Base
{
    public function getUsersByPage(
        int $page,
        int $perPage,
        ?string $name,
        ?string $email
    ): array {
        if ($page < 1) {
            $page = 1;
        }
        if ($perPage < 1) {
            $perPage = self::DEFAULT_PER_PAGE_PAGINATION;
        }

        return $this->userRepository->getUsersByPage(
            $page,
            $perPage,
            $name,
            $email
        );
    }

    public function getAll(): array
    {
        return $this->userRepository->getAll();
    }

    public function getOne(int $userId): object
    {
        if (self::isRedisEnabled() === true) {
            $user = $this->getUserFromCache($userId);
        } else {
            $user = $this->getUserFromDb($userId)->toJson();
        }

        return $user;
    }

    public function getProfilesFor(User $user) 
    {
        return $this->userRepository->getUnswipedProfiles($user);
    }
}
