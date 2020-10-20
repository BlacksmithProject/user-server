<?php

declare(strict_types=1);

namespace App\Domain\Registration\IO;

use App\Domain\Registration\ReadModel\User;

final class Output implements \JsonSerializable
{
    private array $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromUser(User $user): self
    {
        return new self($user->jsonSerialize());
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
