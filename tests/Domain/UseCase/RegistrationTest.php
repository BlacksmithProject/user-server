<?php
declare(strict_types=1);

namespace App\Tests\Domain\UseCase;

use App\Domain\ReadModel\RegisteredUser;
use App\Domain\UseCase\Registration;
use PHPUnit\Framework\TestCase;

final class RegistrationTest extends TestCase
{
    private Registration $registration;

    protected function setUp(): void
    {
        $this->registration = new Registration();
    }

    /** @test */
    public function user_should_be_registered_with_a_valid_email_and_password(): void
    {
        // GIVEN
        $email = 'eddard.start@winterfell.north';
        $password = 'winterIsComing';

        // WHEN
        $user = $this->registration->register($email, $password);

        // THEN
        $this->assertInstanceOf(RegisteredUser::class, $user);
        $this->assertTrue($user->externalIdentifier() !== '');
    }
}
