<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421195756 extends AbstractMigration
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
            ALTER TABLE camera_surveillance ADD CONSTRAINT FK_135483D4F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD sexe VARCHAR(20) DEFAULT NULL, ADD email VARCHAR(100) NOT NULL, ADD type_utilisateur VARCHAR(20) NOT NULL, ADD niveau_experience VARCHAR(20) DEFAULT 'débutant' NOT NULL, ADD points_connexion DOUBLE PRECISION DEFAULT '0' NOT NULL, ADD points_consultation DOUBLE PRECISION DEFAULT '0' NOT NULL, ADD date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD compte_valide TINYINT(1) DEFAULT 0 NOT NULL, DROP pseudo, CHANGE age age INT DEFAULT NULL, CHANGE date_naissance date_naissance DATE DEFAULT NULL, CHANGE type_membre type_membre VARCHAR(50) DEFAULT NULL, CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL, CHANGE genre login VARCHAR(50) NOT NULL, CHANGE image photo_url VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1D1C63B3AA08CB10 ON utilisateur (login)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)
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
            DROP INDEX UNIQ_1D1C63B3AA08CB10 ON utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD pseudo VARCHAR(255) NOT NULL, DROP sexe, DROP email, DROP type_utilisateur, DROP niveau_experience, DROP points_connexion, DROP points_consultation, DROP date_inscription, DROP compte_valide, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE date_naissance date_naissance DATE NOT NULL, CHANGE age age INT NOT NULL, CHANGE type_membre type_membre VARCHAR(50) NOT NULL, CHANGE login genre VARCHAR(50) NOT NULL, CHANGE photo_url image VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
