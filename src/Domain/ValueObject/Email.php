<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\EmailIsInvalid;

final class Email
{
    private string $value;

    /**
     * @throws EmailIsInvalid
     */
    public function __construct(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new EmailIsInvalid($email);
        }
        $this->value = $email;
    }

    public function value(): string
    {
        return $this->value;
    }
}
