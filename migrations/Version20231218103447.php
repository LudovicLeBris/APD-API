<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218103447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE duct_network (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, name VARCHAR(255) NOT NULL, altitude INT NOT NULL, temperature DOUBLE PRECISION NOT NULL, general_material VARCHAR(255) NOT NULL, additional_apd INT NOT NULL, total_linear_apd DOUBLE PRECISION DEFAULT NULL, total_singular_apd DOUBLE PRECISION DEFAULT NULL, total_additional_apd INT DEFAULT NULL, total_apd DOUBLE PRECISION DEFAULT NULL, air LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_3985FBAB166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE duct_section (id INT AUTO_INCREMENT NOT NULL, duct_network_id INT NOT NULL, name VARCHAR(255) NOT NULL, shape VARCHAR(16) NOT NULL, material VARCHAR(255) NOT NULL, flowrate INT NOT NULL, length DOUBLE PRECISION NOT NULL, singularities JSON NOT NULL, additional_apd INT NOT NULL, diameter INT DEFAULT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, equiv_diameter DOUBLE PRECISION DEFAULT NULL, duct_sections_section DOUBLE PRECISION DEFAULT NULL, flowspeed DOUBLE PRECISION DEFAULT NULL, linea_apd DOUBLE PRECISION DEFAULT NULL, singular_apd DOUBLE PRECISION DEFAULT NULL, total_apd DOUBLE PRECISION DEFAULT NULL, air LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_747206F8D9F63851 (duct_network_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, general_altitude INT NOT NULL, general_temperature DOUBLE PRECISION NOT NULL, INDEX IDX_2FB3D0EEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, is_enable TINYINT(1) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE duct_network ADD CONSTRAINT FK_3985FBAB166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE duct_section ADD CONSTRAINT FK_747206F8D9F63851 FOREIGN KEY (duct_network_id) REFERENCES duct_network (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE duct_network DROP FOREIGN KEY FK_3985FBAB166D1F9C');
        $this->addSql('ALTER TABLE duct_section DROP FOREIGN KEY FK_747206F8D9F63851');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('DROP TABLE duct_network');
        $this->addSql('DROP TABLE duct_section');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE user');
    }
}
