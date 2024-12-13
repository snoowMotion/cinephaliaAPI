<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213143907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cinephaliaapi.salle (id SERIAL NOT NULL, cinema_id INT NOT NULL, qualite_id INT NOT NULL, nb_place INT NOT NULL, num_salle VARCHAR(255) NOT NULL, nb_place_pmr INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62EFB41AB4CB84B6 ON cinephaliaapi.salle (cinema_id)');
        $this->addSql('CREATE INDEX IDX_62EFB41AA6338570 ON cinephaliaapi.salle (qualite_id)');
        $this->addSql('ALTER TABLE cinephaliaapi.salle ADD CONSTRAINT FK_62EFB41AB4CB84B6 FOREIGN KEY (cinema_id) REFERENCES cinephaliaapi.cinema (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.salle ADD CONSTRAINT FK_62EFB41AA6338570 FOREIGN KEY (qualite_id) REFERENCES cinephaliaapi.qualite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salle DROP CONSTRAINT fk_4e977e5cb4cb84b6');
        $this->addSql('ALTER TABLE salle DROP CONSTRAINT fk_4e977e5ca6338570');
        $this->addSql('DROP TABLE salle');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE salle (id SERIAL NOT NULL, cinema_id INT NOT NULL, qualite_id INT NOT NULL, nb_place INT NOT NULL, num_salle VARCHAR(255) NOT NULL, nb_place_pmr INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_4e977e5ca6338570 ON salle (qualite_id)');
        $this->addSql('CREATE INDEX idx_4e977e5cb4cb84b6 ON salle (cinema_id)');
        $this->addSql('ALTER TABLE salle ADD CONSTRAINT fk_4e977e5cb4cb84b6 FOREIGN KEY (cinema_id) REFERENCES cinephaliaapi.cinema (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salle ADD CONSTRAINT fk_4e977e5ca6338570 FOREIGN KEY (qualite_id) REFERENCES cinephaliaapi.qualite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.salle DROP CONSTRAINT FK_62EFB41AB4CB84B6');
        $this->addSql('ALTER TABLE cinephaliaapi.salle DROP CONSTRAINT FK_62EFB41AA6338570');
        $this->addSql('DROP TABLE cinephaliaapi.salle');
    }
}
