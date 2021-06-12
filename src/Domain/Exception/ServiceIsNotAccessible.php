<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use Throwable;

final class ServiceIsNotAccessible extends \RuntimeException
{
    public function __construct(string $className, Throwable $previous = null)
    {
        parent::__construct("Service '$className' is not accessible.", 0, $previous);
    }
}
