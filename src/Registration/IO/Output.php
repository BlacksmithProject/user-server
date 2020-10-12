<?php
declare(strict_types=1);

namespace App\Registration\IO;

use App\Registration\ReadModel\ActivationCode;
use App\Registration\ReadModel\User;

class Output implements \JsonSerializable
{
    private array $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromUserAndActivationCode(User $user, ActivationCode $activationCode): self
    {
        return new self([
            'user' => $user->jsonSerialize(),
            'activationCode' => $activationCode->jsonSerialize(),
        ]);
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
