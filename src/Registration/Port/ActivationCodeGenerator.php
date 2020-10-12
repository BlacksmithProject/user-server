<?php

declare(strict_types=1);

namespace App\Registration\Port;

use App\Registration\ReadModel\ActivationCode;
use Symfony\Component\Uid\Uuid;

interface ActivationCodeGenerator
{
    public function generateForUser(Uuid $userUuid): ActivationCode;
}
