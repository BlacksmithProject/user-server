<?php
declare(strict_types=1);

namespace App\Domain\ReadModel;

final class RegisteredUser
{
    private string $externalIdentifier;

    public function __construct(string $externalIdentifier)
    {
        $this->externalIdentifier = $externalIdentifier;
    }

    public function externalIdentifier(): string
    {
        return $this->externalIdentifier;
    }
}
