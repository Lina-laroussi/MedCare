<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507210205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_assurance ADD facture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF0694467F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF0694467F2DEE08 ON fiche_assurance (facture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF0694467F2DEE08');
        $this->addSql('DROP INDEX UNIQ_BF0694467F2DEE08 ON fiche_assurance');
        $this->addSql('ALTER TABLE fiche_assurance DROP facture_id');
    }
}
