<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422200638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE camera_surveillance (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, resolution VARCHAR(20) DEFAULT NULL, detection_mouvement TINYINT(1) DEFAULT 0 NOT NULL, vision_nocturne TINYINT(1) DEFAULT 0 NOT NULL, angle_vision VARCHAR(20) DEFAULT NULL, INDEX IDX_135483D4F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE objet_connecte (id INT AUTO_INCREMENT NOT NULL, id_unique VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, etat VARCHAR(100) NOT NULL, localisation VARCHAR(255) NOT NULL, derniere_interaction DATETIME NOT NULL, connectivite VARCHAR(100) NOT NULL, batterie_pct INT NOT NULL, actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(50) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, date_naissance DATE DEFAULT NULL, sexe VARCHAR(20) DEFAULT NULL, age INT DEFAULT NULL, email VARCHAR(100) NOT NULL, type_membre VARCHAR(50) DEFAULT NULL, photo_url VARCHAR(255) DEFAULT NULL, type_utilisateur VARCHAR(20) NOT NULL, niveau_experience VARCHAR(20) DEFAULT 'débutant' NOT NULL, points_connexion DOUBLE PRECISION DEFAULT '0' NOT NULL, points_consultation DOUBLE PRECISION DEFAULT '0' NOT NULL, date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, compte_valide TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3AA08CB10 (login), UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE camera_surveillance ADD CONSTRAINT FK_135483D4F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE camera_surveillance DROP FOREIGN KEY FK_135483D4F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE camera_surveillance
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE objet_connecte
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
