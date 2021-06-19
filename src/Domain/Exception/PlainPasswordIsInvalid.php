<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\ValueObject\PlainPassword;

final class PlainPasswordIsInvalid extends \InvalidArgumentException
{
    public static function becauseItIsTooShort(int $invalidLength): self
    {
        return new self(sprintf(
            'Plain password is too short: %d long instead of at least %d long.',
            $invalidLength,
            PlainPassword::MINIMUM_LENGTH
        ));
    }
}
