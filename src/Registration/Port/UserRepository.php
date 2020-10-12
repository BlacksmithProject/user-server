<?php

declare(strict_types=1);

namespace App\Registration\Port;

use App\Registration\Model\User;
use Symfony\Component\Uid\Uuid;

interface UserRepository
{
    public function nextId(): Uuid;

    public function save(User $user): void;
}
