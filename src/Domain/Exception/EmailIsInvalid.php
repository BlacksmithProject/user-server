<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use Throwable;

final class EmailIsInvalid extends \InvalidArgumentException
{
    public function __construct(string $invalidEmail)
    {
        parent::__construct("Email $invalidEmail in invalid.");
    }
}
