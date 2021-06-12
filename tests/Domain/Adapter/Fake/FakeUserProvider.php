<?php
declare(strict_types=1);

namespace App\Tests\Domain\Adapter\Fake;

use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserNotFound;
use App\Domain\Model\UserToRegister;
use App\Domain\Port\UserProvider;
use App\Domain\ReadModel\RegisteredUser;
use App\Domain\ValueObject\Email;

final class FakeUserProvider implements UserProvider
{
    /** @var UserToRegister[] */
    public static array $users = [];

    private bool $isUnavailable = false;

    public function isEmailAlreadyUsed(Email $email): bool
    {
        if ($this->isUnavailable) {
            $this->isUnavailable = false;
            throw new ServiceIsNotAccessible(self::class);
        }

        return $this->findByEmail($email) !== null;
    }

    public function getByEmail(Email $email): RegisteredUser
    {
        if ($this->isUnavailable) {
            $this->isUnavailable = false;
            throw new ServiceIsNotAccessible(self::class);
        }

        $user = $this->findByEmail($email);

        if ($user === null) {
            throw new UserNotFound();
        }

        return new RegisteredUser($user->externalIdentifier());
    }

    public static function findByEmail(Email $email): ?UserToRegister
    {
        foreach (self::$users as $user) {
            if ($user->email() === $email->value()) {
                return $user;
            }
        }

        return null;
    }

    public static function resetStorage(): void
    {
        self::$users = [];
    }

    public function makeItUnavailable(): void
    {
        $this->isUnavailable = true;
    }
}
