<?php
declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Port\IdentifierGenerator;
use Ramsey\Uuid\Uuid;

final class UuidGenerator implements IdentifierGenerator
{
    public function generate(): string
    {
        return (Uuid::uuid4())->toString();
    }
}
