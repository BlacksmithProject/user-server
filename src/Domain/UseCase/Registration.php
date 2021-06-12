<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\ReadModel\RegisteredUser;

final class Registration
{

    public function register(string $email, string $password): RegisteredUser
    {
        return new RegisteredUser($email.'aze'.$password);
    }
}
