<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303213507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE membership_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE membership (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users_memberships (user_id INT NOT NULL, membership_id INT NOT NULL)');
        $this->addSql('CREATE INDEX IDX_8F02BF9D216E8781 ON users_memberships (membership_id)');
        $this->addSql('ALTER TABLE users_memberships ADD CONSTRAINT FK_8F02BF9D216E8781 FOREIGN KEY (membership_id) REFERENCES "membership" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8F02BF9D216E8782 ON users_memberships (user_id)');
        $this->addSql('ALTER TABLE users_memberships ADD CONSTRAINT FK_8F02BF9D216E8782 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE membership_id_seq CASCADE');
        $this->addSql('ALTER TABLE membership DROP CONSTRAINT FK_8F02BF9D216E8781');
        $this->addSql('ALTER TABLE membership DROP CONSTRAINT FK_8F02BF9D216E8782');
        $this->addSql('DROP TABLE membership');
        $this->addSql('DROP TABLE users_memberships');
    }
}
