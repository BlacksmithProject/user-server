<?php
declare(strict_types=1);

namespace App\Registration\Model;

use App\Registration\Exception\EmailIsNotValid;
use App\Registration\Exception\PasswordIsTooShort;
use Symfony\Component\Uid\Uuid;

class User
{
    private Uuid $uuid;
    private string $email;
    private string $password;

    /**
     * @throws EmailIsNotValid
     * @throws PasswordIsTooShort
     */
    public function __construct(Uuid $uuid, string $email, string $password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
        $this->email = $email;
        $this->password = $password;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid->jsonSerialize(),
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
