<?php

namespace App\Domain\Port;

use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserIsAlreadyInStorage;
use App\Domain\Model\UserToRegister;

interface UserRepository
{
    /**
     * @throws ServiceIsNotAccessible
     * @throws UserIsAlreadyInStorage
     */
    public function add(UserToRegister $user): void;
}
