<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Adapter\Fake;

use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserIsAlreadyInStorage;
use App\Domain\Model\UserToRegister;
use App\Domain\Port\UserRepository;
use App\Domain\ValueObject\Email;

final class FakeUserRepository implements UserRepository
{
    private bool $isUnavailable = false;

    public function add(UserToRegister $user): void
    {
        if ($this->isUnavailable) {
            $this->isUnavailable = false;
            throw new ServiceIsNotAccessible(self::class);
        }

        if (FakeUserProvider::findByEmail(new Email($user->email())) !== null) {
            throw new UserIsAlreadyInStorage();
        }

        FakeUserProvider::$users[] = $user;
    }

    public function makeItUnavailable(): void
    {
        $this->isUnavailable = true;
    }
}
