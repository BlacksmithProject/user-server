<?php

namespace App\Domain\Port;

use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserNotFound;
use App\Domain\ReadModel\RegisteredUser;
use App\Domain\ValueObject\Email;

interface UserProvider
{
    /** @throws ServiceIsNotAccessible */
    public function isEmailAlreadyUsed(Email $email): bool;

    /**
     * @throws ServiceIsNotAccessible
     * @throws UserNotFound
     */
    public function getByEmail(Email $email): RegisteredUser;
}
