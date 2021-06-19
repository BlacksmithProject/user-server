<?php
declare(strict_types=1);

namespace App\Tests\Unit\Adapter;

use App\Domain\Port\UserProvider;
use App\Domain\Port\UserRepository;
use App\Infrastructure\Adapter\MysqlUserProvider;
use App\Infrastructure\Adapter\MysqlUserRepository;
use App\Tests\Helper\TestConnectionProvider;

final class MysqlUserProviderAndRepositoryTest extends UserProviderAndRepositoryTest
{
    use TestConnectionProvider;

    protected function setUp(): void
    {
        $this->getTestConnection()->executeQuery('TRUNCATE users');
    }

    protected function createUserRepository(): UserRepository
    {
        return new MysqlUserRepository($this->getTestConnection());
    }

    protected function createUnavailableUserRepository(): UserRepository
    {
        return new MysqlUserRepository($this->getWrongConnection());
    }

    protected function createUserProvider(): UserProvider
    {
        return new MysqlUserProvider($this->getTestConnection());
    }

    protected function createUnavailableUserProvider(): UserProvider
    {
        return new MysqlUserProvider($this->getWrongConnection());
    }
}
