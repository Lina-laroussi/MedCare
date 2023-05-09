<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509114100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF069446ED7016E0');
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF069446F61EE8B');
        $this->addSql('DROP INDEX UNIQ_BF069446ED7016E0 ON fiche_assurance');
        $this->addSql('DROP INDEX UNIQ_BF069446F61EE8B ON fiche_assurance');
        $this->addSql('ALTER TABLE fiche_assurance ADD facture_id INT DEFAULT NULL, DROP remboursement_id, DROP facture_id_id');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF0694467F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF0694467F2DEE08 ON fiche_assurance (facture_id)');
        $this->addSql('ALTER TABLE ordonnance ADD qr_code_filename VARCHAR(255) DEFAULT NULL, CHANGE code_ordonnance code_ordonnance VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EFE392FC9B');
        $this->addSql('DROP INDEX UNIQ_C0C0D9EFE392FC9B ON remboursement');
        $this->addSql('ALTER TABLE remboursement CHANGE fiche_assurance_id_id fiche_assurance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EFEE18DED6 FOREIGN KEY (fiche_assurance_id) REFERENCES fiche_assurance (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0C0D9EFEE18DED6 ON remboursement (fiche_assurance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_assurance DROP FOREIGN KEY FK_BF0694467F2DEE08');
        $this->addSql('DROP INDEX UNIQ_BF0694467F2DEE08 ON fiche_assurance');
        $this->addSql('ALTER TABLE fiche_assurance ADD facture_id_id INT DEFAULT NULL, CHANGE facture_id remboursement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF069446ED7016E0 FOREIGN KEY (facture_id_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE fiche_assurance ADD CONSTRAINT FK_BF069446F61EE8B FOREIGN KEY (remboursement_id) REFERENCES remboursement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF069446ED7016E0 ON fiche_assurance (facture_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF069446F61EE8B ON fiche_assurance (remboursement_id)');
        $this->addSql('ALTER TABLE ordonnance DROP qr_code_filename, CHANGE code_ordonnance code_ordonnance VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EFEE18DED6');
        $this->addSql('DROP INDEX UNIQ_C0C0D9EFEE18DED6 ON remboursement');
        $this->addSql('ALTER TABLE remboursement CHANGE fiche_assurance_id fiche_assurance_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EFE392FC9B FOREIGN KEY (fiche_assurance_id_id) REFERENCES fiche_assurance (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0C0D9EFE392FC9B ON remboursement (fiche_assurance_id_id)');
    }
}
