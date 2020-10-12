<?php
declare(strict_types=1);

namespace App\Tests\Registration;

use App\Registration\Exception\EmailIsAlreadyUsed;
use App\Registration\Exception\UserCouldNotBeFound;
use App\Registration\IO\Input;
use App\Registration\Port\ActivationCodeGenerator;
use App\Registration\Port\UserProvider;
use App\Registration\Port\UserRepository;
use App\Registration\ReadModel\ActivationCode;
use App\Registration\ReadModel\User;
use App\Registration\Registration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class RegistrationTest extends TestCase
{
    private const UUID = '31825114-cfa6-4fc9-ac7f-08d257bb1325';

    /** @var UserRepository&MockObject */
    private $repository;
    /** @var UserProvider&MockObject */
    private $provider;
    /** @var ActivationCodeGenerator&MockObject */
    private $activationCodeGenerator;
    private Registration $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);
        $this->provider = $this->createMock(UserProvider::class);
        $this->activationCodeGenerator = $this->createMock(ActivationCodeGenerator::class);
        $this->service = new Registration($this->provider, $this->repository, $this->activationCodeGenerator);
    }

    public function test user can be registered when valid email and password are provided(): void
    {
        // GIVEN
        $input = new Input('john.doe@example.com', 'greatPassword');
        $this->provider
            ->expects($this->once())
            ->method('isEmailAlreadyUsed')
            ->willReturn(false);
        $this->repository
            ->expects($this->once())
            ->method('nextId')
            ->willReturn(Uuid::fromString(self::UUID));
        $this->repository
            ->expects($this->once())
            ->method('save');
        $this->provider
            ->expects($this->once())
            ->method('byUuid')
            ->willReturn(new User(self::UUID, 'john.doe@example.com'));
        $this->activationCodeGenerator
            ->expects($this->once())
            ->method('generateForUser')
            ->willReturn(new ActivationCode('activation-code'));

        // WHEN
        $output = ($this->service)($input);

        // THEN
        self::assertSame([
            'user' => [
                'uuid' => self::UUID,
                'email' => 'john.doe@example.com',
            ],
            'activationCode' => 'activation-code',
        ], $output->jsonSerialize());
    }

    public function test user cannot be registered with an already used email(): void
    {
        // EXPECT
        self::expectException(EmailIsAlreadyUsed::class);

        // GIVEN
        $input = new Input('john.doe@example.com', 'greatPassword');
        $this->provider
            ->expects($this->once())
            ->method('isEmailAlreadyUsed')
            ->willReturn(true);

        // WHEN
        ($this->service)($input);
    }

    public function test that an exception is thrown if user cannot be found after registration(): void
    {
        // EXPECT
        self::expectException(\RuntimeException::class);

        // GIVEN
        $input = new Input('john.doe@example.com', 'greatPassword');
        $this->provider
            ->expects($this->once())
            ->method('isEmailAlreadyUsed')
            ->willReturn(false);
        $this->repository
            ->expects($this->once())
            ->method('nextId')
            ->willReturn(Uuid::fromString(self::UUID));
        $this->repository
            ->expects($this->once())
            ->method('save');

        $this->provider
            ->expects($this->once())
            ->method('byUuid')
            ->willThrowException(new UserCouldNotBeFound());

        // WHEN
        ($this->service)($input);
    }
}
