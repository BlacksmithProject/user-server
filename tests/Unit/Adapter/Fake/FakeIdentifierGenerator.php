<?php
declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Fake;

use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Port\IdentifierGenerator;

final class FakeIdentifierGenerator implements IdentifierGenerator
{
    private bool $isUnavailable = false;

    public function generate(): string
    {
        if ($this->isUnavailable) {
            $this->isUnavailable = false;
            throw new ServiceIsNotAccessible(self::class);
        }

        return 'fake-identifier';
    }

    public function makeItUnavailable(): void
    {
        $this->isUnavailable = true;
    }
}
