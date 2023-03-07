<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306192319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A69A99F4BC');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A62BF23B8F');
        $this->addSql('DROP INDEX idx_fich_id ON consultation');
        $this->addSql('CREATE INDEX IDX_964685A69A99F4BC ON consultation (fiche_medicale_id)');
        $this->addSql('DROP INDEX uniq_ord_id ON consultation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_964685A62BF23B8F ON consultation (ordonnance_id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A69A99F4BC FOREIGN KEY (fiche_medicale_id) REFERENCES fiche_medicale (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A62BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id)');
        $this->addSql('ALTER TABLE fiche_medicale DROP observ, DROP med_history, DROP fam_history');
        $this->addSql('ALTER TABLE ordonnance CHANGE date_de_creation date_de_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A69A99F4BC');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A62BF23B8F');
        $this->addSql('DROP INDEX idx_964685a69a99f4bc ON consultation');
        $this->addSql('CREATE INDEX IDX_fich_id ON consultation (fiche_medicale_id)');
        $this->addSql('DROP INDEX uniq_964685a62bf23b8f ON consultation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ord_id ON consultation (ordonnance_id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A69A99F4BC FOREIGN KEY (fiche_medicale_id) REFERENCES fiche_medicale (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A62BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id)');
        $this->addSql('ALTER TABLE fiche_medicale ADD observ VARCHAR(255) NOT NULL, ADD med_history VARCHAR(255) DEFAULT NULL, ADD fam_history VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ordonnance CHANGE date_de_creation date_de_creation DATE NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) DEFAULT NULL');
    }
}
