<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241213175934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cinephaliaapi.link_reservation_siege (id SERIAL NOT NULL, reservation_id INT NOT NULL, siege_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FC5D7CB2B83297E7 ON cinephaliaapi.link_reservation_siege (reservation_id)');
        $this->addSql('CREATE INDEX IDX_FC5D7CB2BF006E8B ON cinephaliaapi.link_reservation_siege (siege_id)');
        $this->addSql('ALTER TABLE cinephaliaapi.link_reservation_siege ADD CONSTRAINT FK_FC5D7CB2B83297E7 FOREIGN KEY (reservation_id) REFERENCES cinephaliaapi.reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.link_reservation_siege ADD CONSTRAINT FK_FC5D7CB2BF006E8B FOREIGN KEY (siege_id) REFERENCES cinephaliaapi.siege (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation DROP CONSTRAINT FK_A7B067A919EB6921');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation ADD CONSTRAINT FK_A7B067A919EB6921 FOREIGN KEY (client_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES cinephaliaapi."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cinephaliaapi.link_reservation_siege DROP CONSTRAINT FK_FC5D7CB2B83297E7');
        $this->addSql('ALTER TABLE cinephaliaapi.link_reservation_siege DROP CONSTRAINT FK_FC5D7CB2BF006E8B');
        $this->addSql('DROP TABLE cinephaliaapi.link_reservation_siege');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation DROP CONSTRAINT fk_a7b067a919eb6921');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation ADD CONSTRAINT fk_a7b067a919eb6921 FOREIGN KEY (client_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT fk_54fcd59fa76ed395');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT fk_54fcd59fa76ed395 FOREIGN KEY (user_id) REFERENCES cinephaliaapi."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
