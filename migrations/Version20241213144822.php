<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213144822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cinephaliaapi.seance (id SERIAL NOT NULL, salle_id INT NOT NULL, film_id INT NOT NULL, date_debut TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_fin TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_40EE6161DC304035 ON cinephaliaapi.seance (salle_id)');
        $this->addSql('CREATE INDEX IDX_40EE6161567F5183 ON cinephaliaapi.seance (film_id)');
        $this->addSql('ALTER TABLE cinephaliaapi.seance ADD CONSTRAINT FK_40EE6161DC304035 FOREIGN KEY (salle_id) REFERENCES cinephaliaapi.salle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.seance ADD CONSTRAINT FK_40EE6161567F5183 FOREIGN KEY (film_id) REFERENCES cinephaliaapi.film (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cinephaliaapi.seance DROP CONSTRAINT FK_40EE6161DC304035');
        $this->addSql('ALTER TABLE cinephaliaapi.seance DROP CONSTRAINT FK_40EE6161567F5183');
        $this->addSql('DROP TABLE cinephaliaapi.seance');
    }
}
