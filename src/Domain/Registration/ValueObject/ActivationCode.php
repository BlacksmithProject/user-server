<?php

declare(strict_types=1);

namespace App\Domain\Registration\ValueObject;

final class ActivationCode
{
    private string $value;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $expiredAt;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->createdAt = new \DateTimeImmutable();
        $this->expiredAt = $this->createdAt->add(new \DateInterval('P1D'));
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'createdAt' => $this->createdAt,
            'expiredAt' => $this->expiredAt,
        ];
    }
}
