<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213104902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE genre');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER affiche_url SET DEFAULT \'\'');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER age_mini SET DEFAULT 0');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER label SET DEFAULT \'\'');
    }
}
