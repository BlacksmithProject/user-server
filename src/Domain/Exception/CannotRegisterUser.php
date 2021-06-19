<?php
declare(strict_types=1);

namespace App\Domain\Exception;

final class CannotRegisterUser extends \LogicException
{
    public static function becauseEmailIsAlreadyUsed(string $alreadyUsedEmail): self
    {
        return new self("Cannot register user because email $alreadyUsedEmail is already used.");
    }

    public static function becausePasswordFailedToBeEncoded(): self
    {
        return new self("Cannot register user because password failed to be encoded.");
    }
}
