<?php
declare(strict_types=1);

namespace App\Tests\Domain\Adapter;

use App\Domain\Port\UserProvider;
use App\Domain\Port\UserRepository;
use App\Tests\Domain\Adapter\Fake\FakeUserProvider;
use App\Tests\Domain\Adapter\Fake\FakeUserRepository;

final class FakeUserProviderAndRepositoryTest extends UserProviderAndRepositoryTest
{
    protected function createUserProvider(): UserProvider
    {
        return new FakeUserProvider();
    }

    protected function createUserRepository(): UserRepository
    {
        return new FakeUserRepository();
    }

    protected function setUp(): void
    {
        FakeUserProvider::resetStorage();
    }

    protected function createUnavailableUserRepository(): UserRepository
    {
        $userRepository = new FakeUserRepository();
        $userRepository->makeItUnavailable();

        return $userRepository;
    }

    protected function createUnavailableUserProvider(): UserProvider
    {
        $userProvider = new FakeUserProvider();
        $userProvider->makeItUnavailable();

        return $userProvider;
    }
}
