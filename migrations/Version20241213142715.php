<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213142715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE genre');
        $this->addSql('ALTER TABLE cinephaliaapi.film ADD genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cinephaliaapi.film ADD CONSTRAINT FK_2310739C4296D31F FOREIGN KEY (genre_id) REFERENCES cinephaliaapi.genre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2310739C4296D31F ON cinephaliaapi.film (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE genre (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE cinephaliaapi.film DROP CONSTRAINT FK_2310739C4296D31F');
        $this->addSql('DROP INDEX IDX_2310739C4296D31F');
        $this->addSql('ALTER TABLE cinephaliaapi.film DROP genre_id');
    }
}
