<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221120243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF069446F61EE8B');
        $this->addSql('DROP INDEX UNIQ_BF069446F61EE8B ON fiche_assurance');
        $this->addSql('ALTER TABLE fiche_assurance DROP remboursement_id');
        $this->addSql('ALTER TABLE remboursement ADD fiche_assurance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EFEE18DED6 FOREIGN KEY (fiche_assurance_id) REFERENCES fiche_assurance (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0C0D9EFEE18DED6 ON remboursement (fiche_assurance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_assurance ADD remboursement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF069446F61EE8B FOREIGN KEY (remboursement_id) REFERENCES remboursement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF069446F61EE8B ON fiche_assurance (remboursement_id)');
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EFEE18DED6');
        $this->addSql('DROP INDEX UNIQ_C0C0D9EFEE18DED6 ON remboursement');
        $this->addSql('ALTER TABLE remboursement DROP fiche_assurance_id');
    }
}
