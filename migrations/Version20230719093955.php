<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719093955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE diameter (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, diameter INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE material (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roughness DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE TABLE singularity (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, shape VARCHAR(255) NOT NULL, singularity DOUBLE PRECISION NOT NULL, long_name VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE diameter');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE singularity');
    }
}
