<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use Throwable;

final class UserIsAlreadyInStorage extends \LogicException
{
    public function __construct()
    {
        parent::__construct("User is already in storage.");
    }
}
