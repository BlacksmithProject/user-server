<?php

declare(strict_types=1);

namespace App\Domain\Registration\Port;

use App\Domain\Registration\Model\User;
use Symfony\Component\Uid\Uuid;

interface UserRepository
{
    public function nextId(): Uuid;

    public function save(User $user): void;
}
