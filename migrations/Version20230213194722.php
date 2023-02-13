<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213194722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE analyse_medicale (id INT AUTO_INCREMENT NOT NULL, consultation_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, date_realisation VARCHAR(255) NOT NULL, INDEX IDX_14730FF562FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assurance (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cabinet (id INT AUTO_INCREMENT NOT NULL, medecin_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, horaire VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4CED05B04F31A84 (medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cabinet_user (cabinet_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A591D8ED351EC (cabinet_id), INDEX IDX_A591D8EA76ED395 (user_id), PRIMARY KEY(cabinet_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, date DATE NOT NULL, adresse_livraison VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6EEAA67D7F2DEE08 (facture_id), INDEX IDX_6EEAA67D6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, rendez_vous_id INT DEFAULT NULL, ordonnance_id INT DEFAULT NULL, fiche_medicale_id INT DEFAULT NULL, poids DOUBLE PRECISION NOT NULL, taille DOUBLE PRECISION NOT NULL, imc DOUBLE PRECISION NOT NULL, temperature DOUBLE PRECISION NOT NULL, frequence_cardiaque DOUBLE PRECISION NOT NULL, pression_arterielle DOUBLE PRECISION NOT NULL, taux_glycemie DOUBLE PRECISION NOT NULL, symptomes VARCHAR(255) NOT NULL, observation VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_964685A691EF7EAA (rendez_vous_id), UNIQUE INDEX UNIQ_964685A62BF23B8F (ordonnance_id), INDEX IDX_964685A69A99F4BC (fiche_medicale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, etat VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_medicale (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_soin (id INT AUTO_INCREMENT NOT NULL, pharmacien_id INT DEFAULT NULL, assureur_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, remboursement_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_EFC05749CFDB96BE (pharmacien_id), INDEX IDX_EFC0574980F7E20A (assureur_id), INDEX IDX_EFC057496B899279 (patient_id), UNIQUE INDEX UNIQ_EFC05749F61EE8B (remboursement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique_remboursement (id INT AUTO_INCREMENT NOT NULL, montant_annuel DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, commande_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_3170B74BF347EFB (produit_id), INDEX IDX_3170B74B82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, disponibilite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament_fiche_soin (medicament_id INT NOT NULL, fiche_soin_id INT NOT NULL, INDEX IDX_2613F81FAB0D61F7 (medicament_id), INDEX IDX_2613F81F77947AB3 (fiche_soin_id), PRIMARY KEY(medicament_id, fiche_soin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ordonnance_medicament (ordonnance_id INT NOT NULL, medicament_id INT NOT NULL, INDEX IDX_FE7DFAEE2BF23B8F (ordonnance_id), INDEX IDX_FE7DFAEEAB0D61F7 (medicament_id), PRIMARY KEY(ordonnance_id, medicament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parapharmacie (id INT AUTO_INCREMENT NOT NULL, admin_para_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, horaire VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_27D41E87EF186EC8 (admin_para_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacie (id INT AUTO_INCREMENT NOT NULL, pharmacien_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, horaire VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5FC19434CFDB96BE (pharmacien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE planning (id INT AUTO_INCREMENT NOT NULL, medecin_id INT DEFAULT NULL, date DATETIME NOT NULL, UNIQUE INDEX UNIQ_D499BFF64F31A84 (medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, parapharmacie_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, quantite INT NOT NULL, INDEX IDX_29A5EC27D7C4E100 (parapharmacie_id), INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE remboursement (id INT AUTO_INCREMENT NOT NULL, historique_remboursement_id INT DEFAULT NULL, montant_a_rembourser DOUBLE PRECISION NOT NULL, INDEX IDX_C0C0D9EF7AD01569 (historique_remboursement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, planning_medecin_id INT DEFAULT NULL, date DATETIME NOT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_65E8AA0A6B899279 (patient_id), INDEX IDX_65E8AA0A9D521BE4 (planning_medecin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, pharmacie_id INT DEFAULT NULL, medicament_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_4B365660BC6D351B (pharmacie_id), INDEX IDX_4B365660AB0D61F7 (medicament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, assurance_assureur_id INT DEFAULT NULL, assurance_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, date_de_naissance DATE NOT NULL, num_tel VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, groupe_sanguin VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', image VARCHAR(255) NOT NULL, specialite VARCHAR(255) NOT NULL, etat_civil VARCHAR(255) NOT NULL, cin VARCHAR(255) NOT NULL, num_securite_sociale VARCHAR(255) NOT NULL, INDEX IDX_8D93D649E585A0EE (assurance_assureur_id), INDEX IDX_8D93D649B288C3E3 (assurance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE analyse_medicale ADD CONSTRAINT FK_14730FF562FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE cabinet ADD CONSTRAINT FK_4CED05B04F31A84 FOREIGN KEY (medecin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cabinet_user ADD CONSTRAINT FK_A591D8ED351EC FOREIGN KEY (cabinet_id) REFERENCES cabinet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cabinet_user ADD CONSTRAINT FK_A591D8EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A691EF7EAA FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A62BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A69A99F4BC FOREIGN KEY (fiche_medicale_id) REFERENCES fiche_medicale (id)');
        $this->addSql('ALTER TABLE fiche_soin ADD CONSTRAINT FK_EFC05749CFDB96BE FOREIGN KEY (pharmacien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE fiche_soin ADD CONSTRAINT FK_EFC0574980F7E20A FOREIGN KEY (assureur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE fiche_soin ADD CONSTRAINT FK_EFC057496B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE fiche_soin ADD CONSTRAINT FK_EFC05749F61EE8B FOREIGN KEY (remboursement_id) REFERENCES remboursement (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE medicament_fiche_soin ADD CONSTRAINT FK_2613F81FAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medicament_fiche_soin ADD CONSTRAINT FK_2613F81F77947AB3 FOREIGN KEY (fiche_soin_id) REFERENCES fiche_soin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ordonnance_medicament ADD CONSTRAINT FK_FE7DFAEE2BF23B8F FOREIGN KEY (ordonnance_id) REFERENCES ordonnance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ordonnance_medicament ADD CONSTRAINT FK_FE7DFAEEAB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parapharmacie ADD CONSTRAINT FK_27D41E87EF186EC8 FOREIGN KEY (admin_para_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE pharmacie ADD CONSTRAINT FK_5FC19434CFDB96BE FOREIGN KEY (pharmacien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF64F31A84 FOREIGN KEY (medecin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27D7C4E100 FOREIGN KEY (parapharmacie_id) REFERENCES parapharmacie (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE remboursement ADD CONSTRAINT FK_C0C0D9EF7AD01569 FOREIGN KEY (historique_remboursement_id) REFERENCES historique_remboursement (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A9D521BE4 FOREIGN KEY (planning_medecin_id) REFERENCES planning (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660BC6D351B FOREIGN KEY (pharmacie_id) REFERENCES pharmacie (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660AB0D61F7 FOREIGN KEY (medicament_id) REFERENCES medicament (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649E585A0EE FOREIGN KEY (assurance_assureur_id) REFERENCES assurance (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649B288C3E3 FOREIGN KEY (assurance_id) REFERENCES assurance (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE analyse_medicale DROP FOREIGN KEY FK_14730FF562FF6CDF');
        $this->addSql('ALTER TABLE cabinet DROP FOREIGN KEY FK_4CED05B04F31A84');
        $this->addSql('ALTER TABLE cabinet_user DROP FOREIGN KEY FK_A591D8ED351EC');
        $this->addSql('ALTER TABLE cabinet_user DROP FOREIGN KEY FK_A591D8EA76ED395');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D7F2DEE08');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B899279');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A691EF7EAA');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A62BF23B8F');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A69A99F4BC');
        $this->addSql('ALTER TABLE fiche_soin DROP FOREIGN KEY FK_EFC05749CFDB96BE');
        $this->addSql('ALTER TABLE fiche_soin DROP FOREIGN KEY FK_EFC0574980F7E20A');
        $this->addSql('ALTER TABLE fiche_soin DROP FOREIGN KEY FK_EFC057496B899279');
        $this->addSql('ALTER TABLE fiche_soin DROP FOREIGN KEY FK_EFC05749F61EE8B');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BF347EFB');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B82EA2E54');
        $this->addSql('ALTER TABLE medicament_fiche_soin DROP FOREIGN KEY FK_2613F81FAB0D61F7');
        $this->addSql('ALTER TABLE medicament_fiche_soin DROP FOREIGN KEY FK_2613F81F77947AB3');
        $this->addSql('ALTER TABLE ordonnance_medicament DROP FOREIGN KEY FK_FE7DFAEE2BF23B8F');
        $this->addSql('ALTER TABLE ordonnance_medicament DROP FOREIGN KEY FK_FE7DFAEEAB0D61F7');
        $this->addSql('ALTER TABLE parapharmacie DROP FOREIGN KEY FK_27D41E87EF186EC8');
        $this->addSql('ALTER TABLE pharmacie DROP FOREIGN KEY FK_5FC19434CFDB96BE');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF64F31A84');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27D7C4E100');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE remboursement DROP FOREIGN KEY FK_C0C0D9EF7AD01569');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A9D521BE4');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660BC6D351B');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660AB0D61F7');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649E585A0EE');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649B288C3E3');
        $this->addSql('DROP TABLE analyse_medicale');
        $this->addSql('DROP TABLE assurance');
        $this->addSql('DROP TABLE cabinet');
        $this->addSql('DROP TABLE cabinet_user');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE fiche_medicale');
        $this->addSql('DROP TABLE fiche_soin');
        $this->addSql('DROP TABLE historique_remboursement');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE medicament_fiche_soin');
        $this->addSql('DROP TABLE ordonnance');
        $this->addSql('DROP TABLE ordonnance_medicament');
        $this->addSql('DROP TABLE parapharmacie');
        $this->addSql('DROP TABLE pharmacie');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE remboursement');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
