<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Adapter;

use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserIsAlreadyInStorage;
use App\Domain\Model\UserToRegister;
use App\Domain\Port\UserProvider;
use App\Domain\Port\UserRepository;
use App\Domain\ReadModel\RegisteredUser;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\PlainPassword;
use PHPUnit\Framework\TestCase;

abstract class UserProviderAndRepositoryTest extends TestCase
{
    /** @test */
    public function a_user_can_be_added_to_storage(): void
    {
        // GIVEN
        $userRepository = $this->createUserRepository();
        $userProvider = $this->createUserProvider();

        $email = new Email('eddard.stark@winterfell.north');
        $user = new UserToRegister(
            'fake-identifier',
            $email,
            new PlainPassword('winterIsComing')
        );

        // WHEN
        $userRepository->add($user);
        $registeredUser = $userProvider->getByEmail($email);

        // THEN
        $this->assertInstanceOf(RegisteredUser::class, $registeredUser);
    }

    /** @test */
    public function a_user_cannot_be_added_twice_in_storage(): void
    {
        // EXPECT
        $this->expectException(UserIsAlreadyInStorage::class);

        // GIVEN
        $userRepository = $this->createUserRepository();

        $email = new Email('eddard.stark@winterfell.north');
        $user = new UserToRegister(
            'fake-identifier',
            $email,
            new PlainPassword('winterIsComing')
        );

        // WHEN
        $userRepository->add($user);
        $userRepository->add($user);
    }

    /**
     * @test
     * @dataProvider createFailingScenarii
     */
    public function an_exception_is_thrown_when_a_service_is_not_available(callable $scenario): void
    {
        // EXPECT
        $this->expectException(ServiceIsNotAccessible::class);

        // GIVEN
        $externalIdentifier = 'fake-identifier';
        $email = new Email('eddard.stark@winterfell.north');
        $password = new PlainPassword('winterIsComing');
        $userToRegister = new UserToRegister($externalIdentifier, $email, $password);

        // WHEN
        $scenario($userToRegister);
    }

    abstract public function createFailingScenarii(): \Generator;
    abstract protected function createUserRepository(): UserRepository;
    abstract protected function createUnavailableUserRepository(): UserRepository;
    abstract protected function createUserProvider(): UserProvider;
    abstract protected function createUnavailableUserProvider(): UserProvider;
}
