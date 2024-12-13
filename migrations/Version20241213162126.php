<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213162126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cinephaliaapi.siege (id SERIAL NOT NULL, salle_id INT NOT NULL, numero VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4B7E7EB1DC304035 ON cinephaliaapi.siege (salle_id)');
        $this->addSql('ALTER TABLE cinephaliaapi.siege ADD CONSTRAINT FK_4B7E7EB1DC304035 FOREIGN KEY (salle_id) REFERENCES cinephaliaapi.salle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cinephaliaapi.siege DROP CONSTRAINT FK_4B7E7EB1DC304035');
        $this->addSql('DROP TABLE cinephaliaapi.siege');
    }
}
