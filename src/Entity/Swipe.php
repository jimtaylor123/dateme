<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Traits\ArrayOrJsonResponse;

final class Swipe
{
    use ArrayOrJsonResponse;

    public const PREFERENCES = ['male', 'female'];
    public const REQUIRED_FIELDS = ['userId', 'profileId', 'preference'];

    /** @var int */
    private $id;

    /** @var int */
    private $userId;
    
    /** @var int */
    private $profileId;

    /** @var string */
    private $preference;

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

    public function getProfileId(): int
    {
        return $this->profileId;
    }

    public function updateProfileId(int $profileId): self
    {
        $this->profileId = $profileId;

        return $this;
    }

    public function getPreference(): string
    {
        return $this->preference;
    }

    public function updatePreference(string $preference): self
    {
        $this->preference = $preference;

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
