<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213134309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cinephaliaapi.genre (id SERIAL NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER titre SET DEFAULT \'\'');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER synopsis SET DEFAULT \'\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE cinephaliaapi.genre');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER titre DROP DEFAULT');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER synopsis DROP DEFAULT');
    }
}
