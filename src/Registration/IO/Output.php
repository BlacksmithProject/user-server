<?php
declare(strict_types=1);

namespace App\Registration\IO;

use App\Registration\ReadModel\User;

class Output implements \JsonSerializable
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

    public static function fromMessage(string $message): self
    {
        return new self([
            'error' => $message,
        ]);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
