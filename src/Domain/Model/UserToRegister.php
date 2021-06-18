<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Exception\FailedToEncodePassword;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\PlainPassword;

final class UserToRegister
{
    private string $externalIdentifier;
    private Email $email;
    private string $encodedPassword;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    /**
     * @throws FailedToEncodePassword
     */
    public function __construct(
        string        $externalIdentifier,
        Email         $email,
        PlainPassword $plainPassword
    ) {
        $this->externalIdentifier = $externalIdentifier;
        $this->email = $email;
        $encodedPassword = \password_hash($plainPassword->value(), PASSWORD_BCRYPT);

        if ($encodedPassword === false) {
            throw new FailedToEncodePassword();
        }

        $this->encodedPassword = $encodedPassword;

        $now = new \DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function externalIdentifier(): string
    {
        return $this->externalIdentifier;
    }

    public function email(): string
    {
        return $this->email->value();
    }

    public function password(): string
    {
        return $this->encodedPassword;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
