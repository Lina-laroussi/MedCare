<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307194250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation CHANGE symptomes maladie VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ordonnance ADD qr_code_filename VARCHAR(255) DEFAULT NULL, CHANGE code_ordonnance code_ordonnance VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation CHANGE maladie symptomes VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ordonnance DROP qr_code_filename, CHANGE code_ordonnance code_ordonnance VARCHAR(255) NOT NULL');
    }
}
