<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use Throwable;

final class UserNotFound extends \LogicException
{
    public function __construct()
    {
        parent::__construct('User not found.');
    }
}
