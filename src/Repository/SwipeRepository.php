<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Swipe;

final class SwipeRepository extends BaseRepository
{
    public function createSwipe(Swipe $swipe): Swipe
    {
        $query = '
            INSERT INTO `swipes`
                (`userId`, `profileId`, `preference`, `createdAt`)
            VALUES
                (:userId, :profileId, :preference, :createdAt)
        ';
        $statement = $this->database->prepare($query);
 
        $statement->bindParam(':userId', $swipe->getUserId());
        $statement->bindParam(':profileId', $swipe->getProfileId());
        $statement->bindParam(':preference', $swipe->getPreference());
        $statement->bindParam(':createdAt', $swipe->getCreatedAt());
        
        $statement->execute();

        return $this->checkAndGetSwipe((int) $this->database->lastInsertId());
    }

    public function checkAndGetSwipe(int $swipeId): Swipe
    {
        $query = 'SELECT * FROM `swipes` WHERE `id` = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':id', $swipeId);
        $statement->execute();
        $swipe = $statement->fetchObject(Swipe::class);
        if (! $swipe) {
            throw new \App\Exception\Swipe('Swipe not found.', 404);
        }

        return $swipe;
    }

    public function checkForAMatch(Swipe $swipe)
    {
        $query = 'SELECT * FROM `swipes` WHERE `userId` = :profileId and `profileId` = :userId and preference = "yes"';
        $statement = $this->database->prepare($query);
        $statement->bindParam(':profileId', $swipe->getProfileId());
        $statement->bindParam(':userId', $swipe->getUserId());
        $statement->execute();
        $swipe = $statement->fetchObject(Swipe::class);
        if (! $swipe) {
            return false;
        }

        return true;
    }

    public function getSwipes(): array
    {
        $query = 'SELECT * FROM `swipes` ORDER BY `id`';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll();
    }

    // public function getQuerySwipesByPage(): string
    // {
    //     return "
    //         SELECT *
    //         FROM `swipes`
    //         WHERE `name` LIKE CONCAT('%', :name, '%')
    //         AND `description` LIKE CONCAT('%', :description, '%')
    //         ORDER BY `id`
    //     ";
    // }

    // public function getSwipesByPage(
    //     int $page,
    //     int $perPage,
    //     ?string $name,
    //     ?string $description
    // ): array {
    //     $params = [
    //         'name' => is_null($name) ? '' : $name,
    //         'description' => is_null($description) ? '' : $description,
    //     ];
    //     $query = $this->getQuerySwipesByPage();
    //     $statement = $this->database->prepare($query);
    //     $statement->bindParam('name', $params['name']);
    //     $statement->bindParam('description', $params['description']);
    //     $statement->execute();
    //     $total = $statement->rowCount();

    //     return $this->getResultsWithPagination(
    //         $query,
    //         $page,
    //         $perPage,
    //         $params,
    //         $total
    //     );
    // }

}
