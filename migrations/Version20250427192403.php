<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427192403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
       
        $this->addSql(<<<'SQL'
            DROP TABLE code_ville
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D3765F649F2C3FAB ON objet_connecte
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON objet_connecte
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte DROP id, DROP zone_id, DROP description, DROP derniere_interaction, DROP connectivite, CHANGE id_unique id_unique INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte ADD PRIMARY KEY (id_unique)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee DROP FOREIGN KEY FK_7D9DDF9F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON poubelle_connectee
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee CHANGE id id_unique INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee ADD CONSTRAINT FK_7D9DDF9F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id_unique) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee ADD PRIMARY KEY (id_unique)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur DROP code_ville, DROP adresse_ville, DROP statut_verification
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE abribus_intelligent (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, prochains_passages LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ecran_fonctionnel TINYINT(1) DEFAULT 1 NOT NULL, informations_affichees LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_2F6438D1F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE borne_recharge (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, puissance_max DOUBLE PRECISION DEFAULT NULL, type_connecteur VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nombre_points_charge INT DEFAULT 1 NOT NULL, status_occupation VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT 'libre' NOT NULL COLLATE `utf8mb4_unicode_ci`, prix_kwh DOUBLE PRECISION DEFAULT NULL, temps_charge_moyen INT DEFAULT NULL, INDEX IDX_162F2ED6F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE camera_surveillance (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, resolution VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, detection_mouvement TINYINT(1) DEFAULT 0 NOT NULL, vision_nocturne TINYINT(1) DEFAULT 0 NOT NULL, angle_vision VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_135483D4F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capteur_bruit (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, niveau_decibel DOUBLE PRECISION DEFAULT NULL, seuil_alerte DOUBLE PRECISION DEFAULT NULL, derniere_alerte DATETIME DEFAULT NULL, INDEX IDX_E9F80411F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capteur_qualite_air (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, niveau_pm25 DOUBLE PRECISION DEFAULT NULL, niveau_pm10 DOUBLE PRECISION DEFAULT NULL, niveau_co2 DOUBLE PRECISION DEFAULT NULL, niveau_o3 DOUBLE PRECISION DEFAULT NULL, qualite_globale VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, derniere_mesure DATETIME DEFAULT NULL, INDEX IDX_120A9369F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE code_ville (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quartier VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_creation DATETIME NOT NULL, est_utilise TINYINT(1) NOT NULL, utilisateur_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_AF96C08F77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feu_circulation (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, etat_actuel VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT 'rouge' NOT NULL COLLATE `utf8mb4_unicode_ci`, mode_fonctionnement VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT 'normal' NOT NULL COLLATE `utf8mb4_unicode_ci`, duree_cycle INT DEFAULT NULL, priorite_transport_commun TINYINT(1) NOT NULL, detection_vehicules TINYINT(1) NOT NULL, INDEX IDX_BCA878D3F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE historique_consultation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type_element VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, element_id INT DEFAULT NULL, nom_element VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_consultation DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_324EEFC5FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lampadaire_intelligent (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, intensite_lumineuse INT DEFAULT NULL, mode_eclairage VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT 'adaptatif' NOT NULL COLLATE `utf8mb4_unicode_ci`, capteur_mouvement TINYINT(1) NOT NULL, capteur_luminosite TINYINT(1) NOT NULL, heures_fonctionnement VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_DD5BFB6EF520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE parking_intelligent (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, places_totales INT NOT NULL, places_disponibles INT NOT NULL, localisation_precise VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_52216B1F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, coordonnees VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abribus_intelligent ADD CONSTRAINT FK_2F6438D1F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE borne_recharge ADD CONSTRAINT FK_162F2ED6F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE camera_surveillance ADD CONSTRAINT FK_135483D4F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capteur_bruit ADD CONSTRAINT FK_E9F80411F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capteur_qualite_air ADD CONSTRAINT FK_120A9369F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feu_circulation ADD CONSTRAINT FK_BCA878D3F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation ADD CONSTRAINT FK_324EEFC5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lampadaire_intelligent ADD CONSTRAINT FK_DD5BFB6EF520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parking_intelligent ADD CONSTRAINT FK_52216B1F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte ADD id INT AUTO_INCREMENT NOT NULL, ADD zone_id INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD derniere_interaction DATETIME NOT NULL, ADD connectivite VARCHAR(100) NOT NULL, CHANGE id_unique id_unique VARCHAR(255) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte ADD CONSTRAINT FK_D3765F649F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D3765F649F2C3FAB ON objet_connecte (zone_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee MODIFY id_unique INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee DROP FOREIGN KEY FK_7D9DDF9F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON poubelle_connectee
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee CHANGE id_unique id INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee ADD CONSTRAINT FK_7D9DDF9F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE poubelle_connectee ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD code_ville VARCHAR(50) DEFAULT NULL, ADD adresse_ville VARCHAR(255) DEFAULT NULL, ADD statut_verification VARCHAR(20) DEFAULT 'en_attente' NOT NULL
        SQL);
    }
}
