<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\PlainPasswordIsInvalid;

final class PlainPassword
{
    public const MINIMUM_LENGTH = 6;

    private string $value;

    public function __construct(string $plainPassword)
    {
        if (strlen($plainPassword) < self::MINIMUM_LENGTH) {
            throw PlainPasswordIsInvalid::becauseItIsTooShort(strlen($plainPassword));
        }

        $this->value = $plainPassword;
    }

    public function value(): string
    {
        return $this->value;
    }
}
