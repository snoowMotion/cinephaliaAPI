<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407122341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cinephaliaapi.avis (id SERIAL NOT NULL, utilisateur_id INT NOT NULL, reservation_id INT DEFAULT NULL, note INT NOT NULL, commentaire VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2EC5664EFB88E14F ON cinephaliaapi.avis (utilisateur_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2EC5664EB83297E7 ON cinephaliaapi.avis (reservation_id)');
        $this->addSql('COMMENT ON COLUMN cinephaliaapi.avis.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE cinephaliaapi.avis ADD CONSTRAINT FK_2EC5664EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.avis ADD CONSTRAINT FK_2EC5664EB83297E7 FOREIGN KEY (reservation_id) REFERENCES cinephaliaapi.reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE avis DROP CONSTRAINT fk_8f91abf0fb88e14f');
        $this->addSql('ALTER TABLE avis DROP CONSTRAINT fk_8f91abf0b83297e7');
        $this->addSql('DROP TABLE avis');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation DROP CONSTRAINT FK_A7B067A919EB6921');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation ADD CONSTRAINT FK_A7B067A919EB6921 FOREIGN KEY (client_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES cinephaliaapi."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE avis (id SERIAL NOT NULL, utilisateur_id INT NOT NULL, reservation_id INT DEFAULT NULL, note INT NOT NULL, commentaire VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8f91abf0b83297e7 ON avis (reservation_id)');
        $this->addSql('CREATE INDEX idx_8f91abf0fb88e14f ON avis (utilisateur_id)');
        $this->addSql('COMMENT ON COLUMN avis.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT fk_8f91abf0fb88e14f FOREIGN KEY (utilisateur_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT fk_8f91abf0b83297e7 FOREIGN KEY (reservation_id) REFERENCES cinephaliaapi.reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.avis DROP CONSTRAINT FK_2EC5664EFB88E14F');
        $this->addSql('ALTER TABLE cinephaliaapi.avis DROP CONSTRAINT FK_2EC5664EB83297E7');
        $this->addSql('DROP TABLE cinephaliaapi.avis');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER label TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT fk_54fcd59fa76ed395');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT fk_54fcd59fa76ed395 FOREIGN KEY (user_id) REFERENCES cinephaliaapi."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation DROP CONSTRAINT fk_a7b067a919eb6921');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation ADD CONSTRAINT fk_a7b067a919eb6921 FOREIGN KEY (client_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
