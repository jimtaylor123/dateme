<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Traits\ArrayOrJsonResponse;

final class User
{
    use ArrayOrJsonResponse;

    public const GENDERS = ['male', 'female'];
    public const REQUIRED_FIELDS = ['name', 'email', 'password', 'gender', 'dateOfBirth'];

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var string */
    private $gender;

    /** @var DateTime */
    private $dateOfBirth;


    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Name
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function updateName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function updateEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Password
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function updatePassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Date of birth
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth->format('Y-m-d');
    }

    public function updateDateOfBirth(DateTime $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Gender
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    public function updateGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Age
     */
    public function getAge(): int
    {
        $to   = new DateTime('today');
        return $this->dateOfBirth->diff($to)->y;
    }
}
