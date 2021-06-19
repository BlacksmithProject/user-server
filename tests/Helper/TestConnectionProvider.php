<?php
declare(strict_types=1);

namespace App\Tests\Helper;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver;

trait TestConnectionProvider
{
    protected function getTestConnection(): Connection
    {
        return new Connection(
            [
                'dbname' => 'user_server_test',
                'user' => 'root',
                'password' => 'root',
                'host' => 'db:3306',
                'driver' => 'pdo_mysql',
            ],
            new Driver(),
            new Configuration()
        );
    }

    protected function getWrongConnection(): Connection
    {
        return new Connection(
            [],
            new Driver(),
            new Configuration()
        );
    }
}
