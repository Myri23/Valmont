<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427181914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE historique_consultation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type_element VARCHAR(255) NOT NULL, element_id INT DEFAULT NULL, nom_element VARCHAR(255) DEFAULT NULL, date_consultation DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_324EEFC5FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, coordonnees VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation ADD CONSTRAINT FK_324EEFC5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte ADD zone_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte ADD CONSTRAINT FK_D3765F649F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D3765F649F2C3FAB ON objet_connecte (zone_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte DROP FOREIGN KEY FK_D3765F649F2C3FAB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation DROP FOREIGN KEY FK_324EEFC5FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE historique_consultation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE zone
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D3765F649F2C3FAB ON objet_connecte
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE objet_connecte DROP zone_id
        SQL);
    }
}
