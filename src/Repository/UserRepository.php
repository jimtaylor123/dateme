<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

final class UserRepository extends BaseRepository
{
    public function getUser(int $userId): User
    {
        $query = 'SELECT `id`, `name`, `email`, `gender`, `dateOfBirth` FROM `users` WHERE `id` = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam('id', $userId);
        $statement->execute();
        $user = $statement->fetchObject(User::class);
        if (! $user) {
            throw new \App\Exception\User('User not found.', 404);
        }

        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        $query = 'SELECT * FROM `users` WHERE `email` = :email';
        $statement = $this->database->prepare($query);
        $statement->bindParam('email', $email);
        $statement->execute();
        $user = $statement->fetchObject(User::class);
        if (! $user) {
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

    public function getUnswipedProfiles(User $user) : array
    {
        $query = 'SELECT * FROM users 
        WHERE gender != :gender
        AND id not in (
            SELECT profileId FROM swipes WHERE userId = :userId
        )';

        $statement = $this->database->prepare($query);
        $statement->bindParam(':userId', $user->getId());
        $statement->bindParam(':gender', $user->getGender());
        $statement->execute();
        return (array) $statement->FetchAll();
    }

    public function getUsersByPage(
        int $page,
        int $perPage,
        ?string $name,
        ?string $email
    ): array {
        $params = [
            'name' => is_null($name) ? '' : $name,
            'email' => is_null($email) ? '' : $email,
        ];
        $query = $this->getQueryUsersByPage();
        $statement = $this->database->prepare($query);
        $statement->bindParam('name', $params['name']);
        $statement->bindParam('email', $params['email']);
        $statement->execute();
        $total = $statement->rowCount();

        return $this->getResultsWithPagination(
            $query,
            $page,
            $perPage,
            $params,
            $total
        );
    }

    public function getQueryUsersByPage(): string
    {
        return "
            SELECT `id`, `name`, `email`
            FROM `users`
            WHERE `name` LIKE CONCAT('%', :name, '%')
            AND `email` LIKE CONCAT('%', :email, '%')
            ORDER BY `id`
        ";
    }

    public function getAll(): array
    {
        $query = 'SELECT `id`, `name`, `email`, `gender`, `dateOfBirth` FROM `users` ORDER BY `id`';
        $statement = $this->database->prepare($query);
        $statement->execute();

        return (array) $statement->fetchAll();
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
        if (! $user) {
            throw new \App\Exception\User('Login failed: Email or password incorrect.', 400);
        }

        return $user;
    }

    public function create(User $user): User
    {
        $query = '
            INSERT INTO `users`
                (`name`, `email`, `password`, `gender`, `dateOfBirth`)
            VALUES
                (:name, :email, :password, :gender, :dateOfBirth)
        ';
        $statement = $this->database->prepare($query);
        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $gender = $user->getGender();
        $dateOfBirth = $user->getDateOfBirth();
        $statement->bindParam('name', $name);
        $statement->bindParam('email', $email);
        $statement->bindParam('password', $password);
        $statement->bindParam('gender', $gender);
        $statement->bindParam('dateOfBirth', $dateOfBirth);
        $statement->execute();

        return $this->getUser((int) $this->database->lastInsertId());
    }

    public function update(User $user): User
    {
        $query = '
            UPDATE `users` SET `name` = :name, `email` = :email, `gender` = :gender, `dateOfBirth` = :dateOfBirth WHERE `id` = :id
        ';
        $statement = $this->database->prepare($query);
        $id = $user->getId();
        $name = $user->getName();
        $email = $user->getEmail();
        $gender = $user->getGender();
        $dateOfBirth = $user->getDateOfBirth();
        $statement->bindParam('id', $id);
        $statement->bindParam('name', $name);
        $statement->bindParam('email', $email);
        $statement->bindParam('gender', $gender);
        $statement->bindParam('dateOfBirth', $dateOfBirth);
        $statement->execute();

        return $this->getUser((int) $id);
    }

    public function delete(int $userId): void
    {
        $query = 'DELETE FROM `users` WHERE `id` = :id';
        $statement = $this->database->prepare($query);
        $statement->bindParam('id', $userId);
        $statement->execute();
    }

    public function deleteUserTasks(int $userId): void
    {
        $query = 'DELETE FROM `tasks` WHERE `userId` = :userId';
        $statement = $this->database->prepare($query);
        $statement->bindParam('userId', $userId);
        $statement->execute();
    }
}
