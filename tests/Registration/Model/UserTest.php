<?php
declare(strict_types=1);

namespace App\Tests\Registration\Model;

use App\Domain\Registration\Exception\EmailIsNotValid;
use App\Domain\Registration\Exception\PasswordIsTooShort;
use App\Domain\Registration\Model\User;
use App\Domain\Registration\ValueObject\ActivationCode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class UserTest extends TestCase
{
    private const UUID = '31825114-cfa6-4fc9-ac7f-08d257bb1325';

    public function test user can expose its data for persistence(): void
    {
        // GIVEN
        $user = new User(Uuid::fromString(self::UUID), new ActivationCode('fake-code'), 'john.doe@example.com', 'secret');

        // WHEN
        $array = $user->toArray();

        // THEN
        self::assertSame($array['uuid'], self::UUID);
        self::assertSame($array['email'], 'john.doe@example.com');
        self::assertArrayHasKey('password', $array);
        self::assertTrue($array['password'] !== 'secret');
    }

    public function test that email must be valid(): void
    {
        // EXPECT
        self::expectException(EmailIsNotValid::class);

        // GIVEN
        new User(Uuid::fromString(self::UUID), new ActivationCode('fake-code'), 'invalid-email', 'greatPassword');
    }

    public function test that password must be at least 6 characters long(): void
    {
        // EXPECT
        self::expectException(PasswordIsTooShort::class);

        // GIVEN
        new User(Uuid::fromString(self::UUID), new ActivationCode('fake-code'), 'john.doe@example.com', 'short');
    }
}
