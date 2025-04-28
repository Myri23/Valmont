<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427200533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE code_ville (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, adresse VARCHAR(255) NOT NULL, quartier VARCHAR(50) DEFAULT NULL, date_creation DATETIME NOT NULL, est_utilise TINYINT(1) NOT NULL, utilisateur_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_AF96C08F77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE historique_consultation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type_element VARCHAR(255) NOT NULL, element_id INT DEFAULT NULL, nom_element VARCHAR(255) DEFAULT NULL, date_consultation DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_324EEFC5FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation ADD CONSTRAINT FK_324EEFC5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD code_ville VARCHAR(50) DEFAULT NULL, ADD adresse_ville VARCHAR(255) DEFAULT NULL, ADD statut_verification VARCHAR(20) DEFAULT 'en_attente' NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation DROP FOREIGN KEY FK_324EEFC5FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE code_ville
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE historique_consultation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur DROP code_ville, DROP adresse_ville, DROP statut_verification
        SQL);
    }
}
