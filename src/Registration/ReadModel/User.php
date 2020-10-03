<?php
declare(strict_types=1);

namespace App\Registration\ReadModel;

class User implements \JsonSerializable
{
    private string $uuid;
    private string $email;
    private string $activationCode;

    public function __construct(string $uuid, string $email, string $activationCode)
    {
        $this->uuid = $uuid;
        $this->email = $email;
        $this->activationCode = $activationCode;
    }

    public function jsonSerialize()
    {
        return [
            'user' => [
                'uuid' => $this->uuid,
                'email' => $this->email,
            ],
            'activationCode' => $this->activationCode,
        ];
    }
}
