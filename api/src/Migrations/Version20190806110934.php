<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190806110934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE greeting_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE phone_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE phone_feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE phone_has_feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE phone (id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description TEXT DEFAULT NULL, release_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE phone_feature (id INT NOT NULL, name VARCHAR(255) NOT NULL, unit VARCHAR(25) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE phone_has_feature (id INT NOT NULL, phone_id INT NOT NULL, phone_feature_id INT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F86B71B03B7323CB ON phone_has_feature (phone_id)');
        $this->addSql('CREATE INDEX IDX_F86B71B048EF7EEC ON phone_has_feature (phone_feature_id)');
        $this->addSql('ALTER TABLE phone_has_feature ADD CONSTRAINT FK_F86B71B03B7323CB FOREIGN KEY (phone_id) REFERENCES phone (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_has_feature ADD CONSTRAINT FK_F86B71B048EF7EEC FOREIGN KEY (phone_feature_id) REFERENCES phone_feature (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE greeting');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE phone_has_feature DROP CONSTRAINT FK_F86B71B03B7323CB');
        $this->addSql('ALTER TABLE phone_has_feature DROP CONSTRAINT FK_F86B71B048EF7EEC');
        $this->addSql('DROP SEQUENCE phone_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE phone_feature_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE phone_has_feature_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE greeting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE greeting (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE phone');
        $this->addSql('DROP TABLE phone_feature');
        $this->addSql('DROP TABLE phone_has_feature');
    }
}
