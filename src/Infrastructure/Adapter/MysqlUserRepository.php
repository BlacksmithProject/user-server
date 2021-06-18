<?php
declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Domain\Exception\ServiceIsNotAccessible;
use App\Domain\Exception\UserIsAlreadyInStorage;
use App\Domain\Model\UserToRegister;
use App\Domain\Port\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class MysqlUserRepository implements UserRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(UserToRegister $user): void
    {
        try {
            $this->connection->insert(
                'users',
                [
                'email' => $user->email(),
                'password' => $user->password(),
                'created_at' => $user->createdAt(),
                'updated_at' => $user->updatedAt(),
                'external_identifier' => $user->externalIdentifier(),
                ],
                [
                'created_at' => 'datetime_immutable',
                'updated_at' => 'datetime_immutable',
                ]
            );
        } catch (Exception $exception) {
            if ($exception->getPrevious()->getCode() === "23000") {
                throw new UserIsAlreadyInStorage();
            }

            throw new ServiceIsNotAccessible(self::class, $exception);
        }
    }
}
