<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250512180211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cinephaliaapi.avis DROP CONSTRAINT FK_2EC5664EFB88E14F');
        $this->addSql('ALTER TABLE cinephaliaapi.avis ADD CONSTRAINT FK_2EC5664EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER label TYPE BOOLEAN USING label::boolean ');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation DROP CONSTRAINT FK_A7B067A919EB6921');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation ADD CONSTRAINT FK_A7B067A919EB6921 FOREIGN KEY (client_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES cinephaliaapi."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cinephaliaapi.avis DROP CONSTRAINT fk_2ec5664efb88e14f');
        $this->addSql('ALTER TABLE cinephaliaapi.avis ADD CONSTRAINT fk_2ec5664efb88e14f FOREIGN KEY (utilisateur_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.film ALTER label TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE user_roles DROP CONSTRAINT fk_54fcd59fa76ed395');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT fk_54fcd59fa76ed395 FOREIGN KEY (user_id) REFERENCES cinephaliaapi."user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation DROP CONSTRAINT fk_a7b067a919eb6921');
        $this->addSql('ALTER TABLE cinephaliaapi.reservation ADD CONSTRAINT fk_a7b067a919eb6921 FOREIGN KEY (client_id) REFERENCES cinephaliaapi."user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
