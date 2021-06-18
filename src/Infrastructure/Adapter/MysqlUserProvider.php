<?php
declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserNotFound;
use App\Domain\Port\UserProvider;
use App\Domain\ReadModel\RegisteredUser;
use App\Domain\ValueObject\Email;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception;

final class MysqlUserProvider implements UserProvider
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function isEmailAlreadyUsed(Email $email): bool
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->select('COUNT(*)')
                ->from('users')
                ->where('email = :email')
                ->setParameter('email', $email->value())
                ->execute()
                ->fetchOne();

            return (int) $result !== 0;
        } catch (Exception | DriverException $exception) {
            throw new ServiceIsNotAccessible(self::class, $exception);
        }
    }

    public function getByEmail(Email $email): RegisteredUser
    {
        try {
            $userData = $this->connection->createQueryBuilder()
                ->select('external_identifier')
                ->from('users')
                ->where('email = :email')
                ->setParameter('email', $email->value())
                ->execute()
                ->fetchAssociative();
        } catch (DriverException|\Exception $exception) {
            throw new ServiceIsNotAccessible(self::class, $exception);
        }

        if ($userData === false) {
            throw new UserNotFound();
        }

        return new RegisteredUser($userData['external_identifier']);
    }
}
