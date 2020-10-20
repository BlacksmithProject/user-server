<?php

declare(strict_types=1);

namespace App\Domain\Registration\Model;

use App\Domain\Registration\Exception\EmailIsNotValid;
use App\Domain\Registration\Exception\PasswordIsTooShort;
use App\Domain\Registration\ValueObject\ActivationCode;
use Symfony\Component\Uid\Uuid;

final class User
{
    private Uuid $uuid;
    private string $email;
    private string $password;
    private ActivationCode $activationCode;

    /**
     * @throws EmailIsNotValid
     * @throws PasswordIsTooShort
     */
    public function __construct(
        Uuid $uuid,
        ActivationCode $activationCode,
        string $email,
        string $password
    ) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new EmailIsNotValid();
        }

        if (strlen($password) < 6) {
            throw new PasswordIsTooShort();
        }

        $password = \password_hash($password, PASSWORD_ARGON2I);

        if (\is_string($password) === false) {
            throw new \RuntimeException('failed to hash password');
        }

        $this->uuid = $uuid;
        $this->activationCode = $activationCode;
        $this->email = $email;
        $this->password = $password;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid->jsonSerialize(),
            'activationCode' => $this->activationCode,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
