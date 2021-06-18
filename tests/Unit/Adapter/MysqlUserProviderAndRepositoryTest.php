<?php
declare(strict_types=1);

namespace App\Tests\Unit\Adapter;

use App\Domain\Port\UserProvider;
use App\Domain\Port\UserRepository;
use App\Infrastructure\Adapter\MysqlUserProvider;
use App\Infrastructure\Adapter\MysqlUserRepository;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver;

final class MysqlUserProviderAndRepositoryTest extends UserProviderAndRepositoryTest
{
    protected function setUp(): void
    {
        $this->getConnection()->executeQuery('TRUNCATE users');
    }

    protected function createUserRepository(): UserRepository
    {
        return new MysqlUserRepository($this->getConnection());
    }

    protected function createUnavailableUserRepository(): UserRepository
    {
        return new MysqlUserRepository($this->getWrongConnection());
    }

    protected function createUserProvider(): UserProvider
    {
        return new MysqlUserProvider($this->getConnection());
    }

    protected function createUnavailableUserProvider(): UserProvider
    {
        return new MysqlUserProvider($this->getWrongConnection());
    }

    private function getConnection(): Connection
    {
        return new Connection(
            [
                'dbname' => 'user_server',
                'user' => 'root',
                'password' => 'root',
                'host' => 'db:3306',
                'driver' => 'pdo_mysql',
            ],
            new Driver(),
            new Configuration()
        );
    }

    private function getWrongConnection(): Connection
    {
        return new Connection(
            [],
            new Driver(),
            new Configuration()
        );
    }
}
