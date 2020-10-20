<?php

declare(strict_types=1);

namespace App\Domain\Registration\Port;

use App\Domain\Registration\Exception\UserCouldNotBeFound;
use App\Domain\Registration\ReadModel\User;
use Symfony\Component\Uid\Uuid;

interface UserProvider
{
    /**
     * @throws UserCouldNotBeFound
     */
    public function byUuid(Uuid $userUuid): User;

    public function isEmailAlreadyUsed(string $email): bool;
}
