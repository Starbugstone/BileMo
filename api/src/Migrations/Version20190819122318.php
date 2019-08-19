<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190819122318 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE client_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client_user (id INT NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE client_user_client (client_user_id INT NOT NULL, client_id INT NOT NULL, PRIMARY KEY(client_user_id, client_id))');
        $this->addSql('CREATE INDEX IDX_A35D3A53F55397E8 ON client_user_client (client_user_id)');
        $this->addSql('CREATE INDEX IDX_A35D3A5319EB6921 ON client_user_client (client_id)');
        $this->addSql('ALTER TABLE client_user_client ADD CONSTRAINT FK_A35D3A53F55397E8 FOREIGN KEY (client_user_id) REFERENCES client_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE client_user_client ADD CONSTRAINT FK_A35D3A5319EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE client_user_client DROP CONSTRAINT FK_A35D3A53F55397E8');
        $this->addSql('DROP SEQUENCE client_user_id_seq CASCADE');
        $this->addSql('DROP TABLE client_user');
        $this->addSql('DROP TABLE client_user_client');
    }
}
