<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241228140145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cinephaliaapi.link_reservation_siege ADD seance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cinephaliaapi.link_reservation_siege ADD CONSTRAINT FK_FC5D7CB2E3797A94 FOREIGN KEY (seance_id) REFERENCES cinephaliaapi.seance (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FC5D7CB2E3797A94 ON cinephaliaapi.link_reservation_siege (seance_id)');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation DROP CONSTRAINT FK_A7B067A919EB6921');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation ADD CONSTRAINT FK_A7B067A919EB6921 FOREIGN KEY (client_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES cinephaliaapi."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cinephaliaapi.link_reservation_siege DROP CONSTRAINT FK_FC5D7CB2E3797A94');
        $this->addSql('DROP INDEX IDX_FC5D7CB2E3797A94');
        $this->addSql('ALTER TABLE cinephaliaapi.link_reservation_siege DROP seance_id');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation DROP CONSTRAINT fk_a7b067a919eb6921');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation ADD CONSTRAINT fk_a7b067a919eb6921 FOREIGN KEY (client_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT fk_54fcd59fa76ed395');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT fk_54fcd59fa76ed395 FOREIGN KEY (user_id) REFERENCES cinephaliaapi."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
