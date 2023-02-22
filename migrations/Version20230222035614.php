<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222035614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EFEE18DED6');
        $this->addSql('DROP INDEX UNIQ_C0C0D9EFEE18DED6 ON remboursement');
        $this->addSql('ALTER TABLE remboursement CHANGE fiche_assurance_id relation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EF3256915B FOREIGN KEY (relation_id) REFERENCES fiche_assurance (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0C0D9EF3256915B ON remboursement (relation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EF3256915B');
        $this->addSql('DROP INDEX UNIQ_C0C0D9EF3256915B ON remboursement');
        $this->addSql('ALTER TABLE remboursement CHANGE relation_id fiche_assurance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EFEE18DED6 FOREIGN KEY (fiche_assurance_id) REFERENCES fiche_assurance (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0C0D9EFEE18DED6 ON remboursement (fiche_assurance_id)');
    }
}
