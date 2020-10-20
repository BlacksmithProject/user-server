<?php

declare(strict_types=1);

namespace App\Domain\Registration\Port;

interface ActivationCodeGenerator
{
    public function generate(): string;
}
