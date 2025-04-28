<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428062803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE abribus_intelligent (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, prochains_passages LONGTEXT DEFAULT NULL, ecran_fonctionnel TINYINT(1) DEFAULT 1 NOT NULL, informations_affichees LONGTEXT DEFAULT NULL, INDEX IDX_2F6438D1F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE borne_recharge (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, puissance_max DOUBLE PRECISION DEFAULT NULL, type_connecteur VARCHAR(50) DEFAULT NULL, nombre_points_charge INT DEFAULT 1 NOT NULL, status_occupation VARCHAR(20) DEFAULT 'libre' NOT NULL, prix_kwh DOUBLE PRECISION DEFAULT NULL, temps_charge_moyen INT DEFAULT NULL, INDEX IDX_162F2ED6F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE camera_surveillance (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, resolution VARCHAR(20) DEFAULT NULL, detection_mouvement TINYINT(1) DEFAULT 0 NOT NULL, vision_nocturne TINYINT(1) DEFAULT 0 NOT NULL, angle_vision VARCHAR(20) DEFAULT NULL, INDEX IDX_135483D4F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capteur_bruit (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, niveau_decibel DOUBLE PRECISION DEFAULT NULL, seuil_alerte DOUBLE PRECISION DEFAULT NULL, derniere_alerte DATETIME DEFAULT NULL, INDEX IDX_E9F80411F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE capteur_qualite_air (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, niveau_pm25 DOUBLE PRECISION DEFAULT NULL, niveau_pm10 DOUBLE PRECISION DEFAULT NULL, niveau_co2 DOUBLE PRECISION DEFAULT NULL, niveau_o3 DOUBLE PRECISION DEFAULT NULL, qualite_globale VARCHAR(20) DEFAULT NULL, derniere_mesure DATETIME DEFAULT NULL, INDEX IDX_120A9369F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE feu_circulation (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, etat_actuel VARCHAR(20) DEFAULT 'rouge' NOT NULL, mode_fonctionnement VARCHAR(20) DEFAULT 'normal' NOT NULL, duree_cycle INT DEFAULT NULL, priorite_transport_commun TINYINT(1) NOT NULL, detection_vehicules TINYINT(1) NOT NULL, INDEX IDX_BCA878D3F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE historique_consultation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type_element VARCHAR(255) NOT NULL, element_id INT DEFAULT NULL, nom_element VARCHAR(255) DEFAULT NULL, date_consultation DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_324EEFC5FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE lampadaire_intelligent (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, intensite_lumineuse INT DEFAULT NULL, mode_eclairage VARCHAR(20) DEFAULT 'adaptatif' NOT NULL, capteur_mouvement TINYINT(1) NOT NULL, capteur_luminosite TINYINT(1) NOT NULL, heures_fonctionnement VARCHAR(100) DEFAULT NULL, INDEX IDX_DD5BFB6EF520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE parking_intelligent (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, places_totales INT NOT NULL, places_disponibles INT NOT NULL, localisation_precise VARCHAR(100) NOT NULL, INDEX IDX_52216B1F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE abribus_intelligent ADD CONSTRAINT FK_2F6438D1F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE borne_recharge ADD CONSTRAINT FK_162F2ED6F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE camera_surveillance ADD CONSTRAINT FK_135483D4F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capteur_bruit ADD CONSTRAINT FK_E9F80411F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capteur_qualite_air ADD CONSTRAINT FK_120A9369F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feu_circulation ADD CONSTRAINT FK_BCA878D3F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation ADD CONSTRAINT FK_324EEFC5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lampadaire_intelligent ADD CONSTRAINT FK_DD5BFB6EF520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parking_intelligent ADD CONSTRAINT FK_52216B1F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_D3765F649695E39A ON objet_connecte
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte ADD description LONGTEXT DEFAULT NULL, ADD derniere_interaction DATETIME NOT NULL, ADD connectivite VARCHAR(100) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE abribus_intelligent DROP FOREIGN KEY FK_2F6438D1F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE borne_recharge DROP FOREIGN KEY FK_162F2ED6F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE camera_surveillance DROP FOREIGN KEY FK_135483D4F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capteur_bruit DROP FOREIGN KEY FK_E9F80411F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE capteur_qualite_air DROP FOREIGN KEY FK_120A9369F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE feu_circulation DROP FOREIGN KEY FK_BCA878D3F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation DROP FOREIGN KEY FK_324EEFC5FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE lampadaire_intelligent DROP FOREIGN KEY FK_DD5BFB6EF520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parking_intelligent DROP FOREIGN KEY FK_52216B1F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE abribus_intelligent
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE borne_recharge
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE camera_surveillance
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capteur_bruit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE capteur_qualite_air
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE feu_circulation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE historique_consultation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE lampadaire_intelligent
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE parking_intelligent
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte DROP description, DROP derniere_interaction, DROP connectivite
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_D3765F649695E39A ON objet_connecte (id_unique)
        SQL);
    }
}
