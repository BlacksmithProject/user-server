<?php

declare(strict_types=1);

namespace App\Registration\ReadModel;

final class ActivationCode implements \JsonSerializable
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function jsonSerialize(): string
    {
        return $this->token;
    }
}
