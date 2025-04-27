<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427150130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE historique_consultation (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type_element VARCHAR(255) NOT NULL, element_id INT DEFAULT NULL, date_consultation DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_324EEFC5FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation ADD CONSTRAINT FK_324EEFC5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_connexion DROP FOREIGN KEY FK_C018B2D4FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_connexion ADD CONSTRAINT FK_C018B2D4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_consultation DROP FOREIGN KEY FK_324EEFC5FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE historique_consultation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_connexion DROP FOREIGN KEY FK_C018B2D4FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE historique_connexion ADD CONSTRAINT FK_C018B2D4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
    }
}
