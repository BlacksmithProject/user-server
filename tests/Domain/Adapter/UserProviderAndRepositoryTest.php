<?php
declare(strict_types=1);

namespace App\Tests\Domain\Adapter;

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

    /** @test */
    public function an_exception_is_thrown_when_user_repository_is_unavailable_while_add_is_called(): void
    {
        // EXPECT
        $this->expectException(ServiceIsNotAccessible::class);

        // GIVEN
        $userRepository = $this->createUnavailableUserRepository();

        $email = new Email('eddard.stark@winterfell.north');
        $user = new UserToRegister(
            'fake-identifier',
            $email,
            new PlainPassword('winterIsComing')
        );

        // WHEN
        $userRepository->add($user);
    }

    /** @test */
    public function an_exception_is_thrown_when_user_provider_is_unavailable_while_isEmailAlreadyUsed_is_called(): void
    {
        // EXPECT
        $this->expectException(ServiceIsNotAccessible::class);

        // GIVEN
        $userProvider = $this->createUnavailableUserProvider();

        $email = new Email('eddard.stark@winterfell.north');

        // WHEN
        $userProvider->isEmailAlreadyUsed($email);
    }

    /** @test */
    public function an_exception_is_thrown_when_user_provider_is_unavailable_while_getByEmail_is_called(): void
    {
        // EXPECT
        $this->expectException(ServiceIsNotAccessible::class);

        // GIVEN
        $userProvider = $this->createUnavailableUserProvider();

        $email = new Email('eddard.stark@winterfell.north');

        // WHEN
        $userProvider->getByEmail($email);
    }

    abstract protected function createUserRepository(): UserRepository;
    abstract protected function createUnavailableUserRepository(): UserRepository;
    abstract protected function createUserProvider(): UserProvider;
    abstract protected function createUnavailableUserProvider(): UserProvider;
}
