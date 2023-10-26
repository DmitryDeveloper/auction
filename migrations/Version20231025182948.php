<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025182948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, photo_path VARCHAR(255) DEFAULT NULL, date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, role VARCHAR(30) NOT NULL, email VARCHAR(50) NOT NULL, phone VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_address (id INT NOT NULL, user_id_id INT NOT NULL, country VARCHAR(100) NOT NULL, city VARCHAR(50) NOT NULL, street VARCHAR(100) DEFAULT NULL, house VARCHAR(15) DEFAULT NULL, flat VARCHAR(15) DEFAULT NULL, postal_code VARCHAR(15) DEFAULT NULL, details VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5543718B9D86650F ON user_address (user_id_id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_address_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_address DROP CONSTRAINT FK_5543718B9D86650F');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_address');
    }
}
