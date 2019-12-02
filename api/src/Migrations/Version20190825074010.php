<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190825074010 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE phone_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE phone_feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE phone_has_feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE phone_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455F85E0677 ON client (username)');
        $this->addSql('CREATE TABLE client_user (id INT NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client_user_client (client_user_id INT NOT NULL, client_id INT NOT NULL, PRIMARY KEY(client_user_id, client_id))');
        $this->addSql('CREATE INDEX IDX_A35D3A53F55397E8 ON client_user_client (client_user_id)');
        $this->addSql('CREATE INDEX IDX_A35D3A5319EB6921 ON client_user_client (client_id)');
        $this->addSql('CREATE TABLE phone (id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description TEXT DEFAULT NULL, release_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE phone_feature (id INT NOT NULL, name VARCHAR(255) NOT NULL, unit VARCHAR(25) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F95F9545E237E06 ON phone_feature (name)');
        $this->addSql('CREATE TABLE phone_has_feature (id INT NOT NULL, phone_id INT NOT NULL, phone_feature_id INT NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F86B71B03B7323CB ON phone_has_feature (phone_id)');
        $this->addSql('CREATE INDEX IDX_F86B71B048EF7EEC ON phone_has_feature (phone_feature_id)');
        $this->addSql('CREATE TABLE phone_image (id INT NOT NULL, phone_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_366771453B7323CB ON phone_image (phone_id)');
        $this->addSql('ALTER TABLE client_user_client ADD CONSTRAINT FK_A35D3A53F55397E8 FOREIGN KEY (client_user_id) REFERENCES client_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_user_client ADD CONSTRAINT FK_A35D3A5319EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_has_feature ADD CONSTRAINT FK_F86B71B03B7323CB FOREIGN KEY (phone_id) REFERENCES phone (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_has_feature ADD CONSTRAINT FK_F86B71B048EF7EEC FOREIGN KEY (phone_feature_id) REFERENCES phone_feature (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_image ADD CONSTRAINT FK_366771453B7323CB FOREIGN KEY (phone_id) REFERENCES phone (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE client_user_client DROP CONSTRAINT FK_A35D3A5319EB6921');
        $this->addSql('ALTER TABLE client_user_client DROP CONSTRAINT FK_A35D3A53F55397E8');
        $this->addSql('ALTER TABLE phone_has_feature DROP CONSTRAINT FK_F86B71B03B7323CB');
        $this->addSql('ALTER TABLE phone_image DROP CONSTRAINT FK_366771453B7323CB');
        $this->addSql('ALTER TABLE phone_has_feature DROP CONSTRAINT FK_F86B71B048EF7EEC');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE phone_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE phone_feature_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE phone_has_feature_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE phone_image_id_seq CASCADE');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_user');
        $this->addSql('DROP TABLE client_user_client');
        $this->addSql('DROP TABLE phone');
        $this->addSql('DROP TABLE phone_feature');
        $this->addSql('DROP TABLE phone_has_feature');
        $this->addSql('DROP TABLE phone_image');
    }
}
