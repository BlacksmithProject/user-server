<?php
declare(strict_types=1);

namespace App\Tests\Registration;

use App\Registration\Exception\UserCouldNotBeFound;
use App\Registration\IO\Input;
use App\Registration\Port\UserProvider;
use App\Registration\Port\UserRepository;
use App\Registration\ReadModel\User;
use App\Registration\Registration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class RegistrationTest extends TestCase
{
    private const UUID = '31825114-cfa6-4fc9-ac7f-08d257bb1325';

    /** @var UserRepository&MockObject */
    private $repository;
    /** @var UserProvider&MockObject */
    private $provider;
    private Registration $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);
        $this->provider = $this->createMock(UserProvider::class);
        $this->service = new Registration($this->repository, $this->provider);
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
            ->willReturn(new User(self::UUID, 'john.doe@example.com', 'activation-code'));

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
        // GIVEN
        $input = new Input('john.doe@example.com', 'greatPassword');
        $this->provider
            ->expects($this->once())
            ->method('isEmailAlreadyUsed')
            ->willReturn(true);

        // WHEN
        $output = ($this->service)($input);

        // THEN
        self::assertSame([
            'error' => 'email is already used',
        ], $output->jsonSerialize());
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
