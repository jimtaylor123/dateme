<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Slim\Http\Request;

final class UserRepository extends BaseRepository
{
    /**
     * Statement
     */
    private $profileStatement;

    /**
     * Query
     */
    private $profileQuery;

    public function getUser(int $userId): User
    {
        $query = 'SELECT `id`, `name`, `email`, `gender`, `dateOfBirth`, `lat`, `lng` FROM `users` WHERE `id` = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam('id', $userId);
        $statement->execute();
        $user = $statement->fetchObject(User::class);
        if (!$user) {
            throw new \App\Exception\User('User not found.', 404);
        }

        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        $query = 'SELECT `id`, `name`, `email`, `gender`, `dateOfBirth`, `lat`, `lng` FROM `users` WHERE `email` = :email';
        $statement = $this->database->prepare($query);
        $statement->bindParam('email', $email);
        $statement->execute();
        $user = $statement->fetchObject(User::class);
        if (!$user) {
            throw new \App\Exception\User('User not found.', 404);
        }

        return $user;
    }

    public function checkUserByEmail(string $email): void
    {
        $query = 'SELECT * FROM `users` WHERE `email` = :email';
        $statement = $this->database->prepare($query);
        $statement->bindParam('email', $email);
        $statement->execute();
        $user = $statement->fetchObject();
        if ($user) {
            throw new \App\Exception\User('Email already exists.', 400);
        }
    }

    public function getUnswipedProfiles(User $user, Request $request): array
    {
        $this->profileQuery = $this->baseQuery();

        $this->params[':id'] = $this->params[':userId'] = $user->getId();

        $this->filterByGender($request, $user);
        $this->filterByAge($request);
        $this->orderResults($request);

        $this->profileStatement = $this->database->prepare($this->profileQuery);

        foreach ($this->params as $key => $param) {
            $this->profileStatement->bindParam($key, $param);
        }

        $this->profileStatement->execute();
        return (array) $this->profileStatement->FetchAll();
    }

    private function baseQuery()
    {
        return 'SELECT
        u.id,
        `name`,
        `email`,
        `gender`,
        `dateOfBirth`,
        `lat`,
        `lng`,
        s.profileId,
        COUNT(NULLIF(profileId, \'\')) AS yesSwipes,
        (3959 * acos(cos(radians(37)) * cos(radians(lat)) * cos(radians(lng) - radians(- 122)) + sin(radians(37)) * sin(radians(lat)))) AS distance
        FROM
            `users` u
            LEFT JOIN swipes s ON u.id = s.profileId
        WHERE
            u.id not in (SELECT profileId FROM swipes WHERE userId = :id)
            AND (s.preference = \'yes\' or s.preference IS NULL)';
    }

    private function filterByGender(Request $request, User $user)
    {
        $genders = $this->getGenders($request, $user);
        if ($genders) {
            $this->profileQuery .= ' AND u.gender IN (' . '"' . implode('","', $genders) . '"' . ') ';
        } else {
            // In the absence of any other filter assume males want females and vice versa, and 'other' is interested in anyone. 
            // This is a dangerous assumption! 
            $userGender = $user->getGender();
            switch ($userGender) {
                case 'male':
                    $this->profileQuery .= ' AND u.gender = "female"';
                    break;
                case 'female':
                    $this->profileQuery .= ' AND u.gender = "male"';
                    break;
                    // case 'other':
                    //     break;
            }
        }
    }

    /**
     * Genders will be provided as male, female or other, in any order, seperated by commas
     */
    private function getGenders(Request $request)
    {
        if (!$request->getQueryParam('gender')) {
            return false;
        }
        $genders = explode(",", $request->getQueryParam('gender'));
        return $genders;
    }

    private function filterByAge(Request $request)
    {
        $minAge = $request->getQueryParam('minAge');
        $maxAge = $request->getQueryParam('maxAge');

        if ($minAge) {
            $latest = (new \DateTime('now'))->modify("-$minAge years")->format('Y-m-d');
            $this->params[':latest'] = $latest;
            $this->profileQuery .= " AND u.dateOfBirth < :latest";
        }

        if ($maxAge) {
            $earliest = (new \DateTime('now'))->modify("-$maxAge years")->format('Y-m-d');
            $this->params[':earliest'] = $earliest;
            $this->profileQuery .= " AND u.dateOfBirth > :earliest";
        }
    }

    /**
     * Orders the results by both popularity and distance
     * 
     * We will assume the user wants to order by distance first, then popularity, unless request flag "orderByPopular=first" is set
     */
    private function orderResults(Request $request)
    {
        $this->profileQuery .= " AND u.id != :userId GROUP BY u.id";

        if( $request->getQueryParam('orderByPopular') === "first"){
            $this->query .= " ORDER BY yesSwipes desc, distance desc;";
        } else {
            $this->query .= " ORDER BY distance desc, yesSwipes desc;";
        }
    }

    public function loginUser(string $email, string $password): User
    {
        $query = '
            SELECT *
            FROM `users`
            WHERE `email` = :email AND `password` = :password
            ORDER BY `id`
        ';
        $statement = $this->database->prepare($query);
        $statement->bindParam('email', $email);
        $statement->bindParam('password', $password);
        $statement->execute();
        $user = $statement->fetchObject(User::class);
        if (!$user) {
            throw new \App\Exception\User('Login failed: Email or password incorrect.', 400);
        }

        return $user;
    }

    public function create(User $user): User
    {
        $query = '
            INSERT INTO `users`
                (`name`, `email`, `password`, `gender`, `dateOfBirth`, `lat`, `lng`)
            VALUES
                (:name, :email, :password, :gender, :dateOfBirth, :lat, :lng)
        ';
        $statement = $this->database->prepare($query);

        $statement->bindParam('name', $user->getName());
        $statement->bindParam('email', $user->getEmail());
        $statement->bindParam('password', $user->getPassword());
        $statement->bindParam('gender', $user->getGender());
        $statement->bindParam('dateOfBirth', $user->getDateOfBirth());
        $statement->bindParam('lat', $user->getLat());
        $statement->bindParam('lng', $user->getLng());
        $statement->execute();

        return $this->getUser((int) $this->database->lastInsertId());
    }
}
