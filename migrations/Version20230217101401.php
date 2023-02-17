<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217101401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE num_tel num_tel VARCHAR(255) DEFAULT NULL, CHANGE age age VARCHAR(255) DEFAULT NULL, CHANGE date_de_naissance date_de_naissance DATE DEFAULT NULL, CHANGE sexe sexe VARCHAR(255) DEFAULT NULL, CHANGE specialite specialite VARCHAR(255) DEFAULT NULL, CHANGE cin cin VARCHAR(255) DEFAULT NULL, CHANGE num_securite_sociale num_securite_sociale INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE date_de_modification date_de_modification DATE DEFAULT NULL, CHANGE etat etat VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE num_tel num_tel VARCHAR(255) NOT NULL, CHANGE age age VARCHAR(255) NOT NULL, CHANGE date_de_naissance date_de_naissance DATE NOT NULL, CHANGE sexe sexe VARCHAR(255) NOT NULL, CHANGE specialite specialite VARCHAR(255) NOT NULL, CHANGE cin cin VARCHAR(255) NOT NULL, CHANGE num_securite_sociale num_securite_sociale INT NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE date_de_modification date_de_modification DATE NOT NULL, CHANGE etat etat VARCHAR(255) NOT NULL');
    }
}
