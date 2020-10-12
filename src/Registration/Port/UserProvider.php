<?php

declare(strict_types=1);

namespace App\Registration\Port;

use App\Registration\Exception\UserCouldNotBeFound;
use App\Registration\ReadModel\User;
use Symfony\Component\Uid\Uuid;

interface UserProvider
{
    /**
     * @throws UserCouldNotBeFound
     */
    public function byUuid(Uuid $userUuid): User;

    public function isEmailAlreadyUsed(string $email): bool;
}
