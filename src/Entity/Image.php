<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Traits\ArrayOrJsonResponse;

final class Image
{
    use ArrayOrJsonResponse;

    public const MIME_TYPES = ['image/jpeg', 'image/jpg', 'image/png'];

    /** @var int */
    private $id;

    /** @var int */
    private $userId;
    
    /** @var string */
    private $name;

    /** @var DateTime */
    private $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function updateUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function updateName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt->format('Y-m-d');
    }

    public function updateCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
