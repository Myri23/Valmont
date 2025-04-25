<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424201018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE parking_intelligent (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, places_totales INT NOT NULL, places_disponibles INT NOT NULL, localisation_precise VARCHAR(100) NOT NULL, INDEX IDX_52216B1F520CF5A (objet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parking_intelligent ADD CONSTRAINT FK_52216B1F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE parking_intelligent DROP FOREIGN KEY FK_52216B1F520CF5A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE parking_intelligent
        SQL);
    }
}
