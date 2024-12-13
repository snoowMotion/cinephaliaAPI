<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213143812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE salle (id SERIAL NOT NULL, cinema_id INT NOT NULL, qualite_id INT NOT NULL, nb_place INT NOT NULL, num_salle VARCHAR(255) NOT NULL, nb_place_pmr INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4E977E5CB4CB84B6 ON salle (cinema_id)');
        $this->addSql('CREATE INDEX IDX_4E977E5CA6338570 ON salle (qualite_id)');
        $this->addSql('ALTER TABLE salle ADD CONSTRAINT FK_4E977E5CB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinephaliaapi.cinema (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salle ADD CONSTRAINT FK_4E977E5CA6338570 FOREIGN KEY (qualite_id) REFERENCES cinephaliaapi.qualite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE salle DROP CONSTRAINT FK_4E977E5CB4CB84B6');
        $this->addSql('ALTER TABLE salle DROP CONSTRAINT FK_4E977E5CA6338570');
        $this->addSql('DROP TABLE salle');
    }
}
