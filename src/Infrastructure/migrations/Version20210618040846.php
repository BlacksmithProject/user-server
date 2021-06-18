<?php

declare(strict_types=1);

namespace App\Infrastructure\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210618040846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE users (
                id INT NOT NULL AUTO_INCREMENT,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                external_identifier VARCHAR(255) UNIQUE NOT NULL,
                PRIMARY KEY (id)
            );
        ');
    }

    public function down(Schema $schema): void
    {

    }
}
