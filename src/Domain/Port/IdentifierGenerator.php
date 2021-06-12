<?php

namespace App\Domain\Port;

use App\Domain\Exception\ServiceIsNotAccessible;

interface IdentifierGenerator
{
    /** @throws ServiceIsNotAccessible */
    public function generate(): string;
}
