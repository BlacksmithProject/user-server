<?php
declare(strict_types=1);

namespace App\Registration\ReadModel;

class User implements \JsonSerializable
{
    private string $uuid;
    private string $email;

    public function __construct(string $uuid, string $email)
    {
        $this->uuid = $uuid;
        $this->email = $email;
    }

    public function jsonSerialize()
    {
        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
        ];
    }
}
