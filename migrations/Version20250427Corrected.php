<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250427Corrected extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Renommer les colonnes ID et corriger les contraintes';
    }

    public function up(Schema $schema): void
    {
        // 1. Supprimer d'abord la contrainte de clé étrangère
        $this->addSql('ALTER TABLE poubelle_connectee DROP FOREIGN KEY FK_7D9DDF9F520CF5A');
        
        // 2. Désactiver les vérifications de clé étrangère temporairement
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        
        // 3. Renommer les colonnes id en id_unique
        $this->addSql('ALTER TABLE objet_connecte CHANGE id id_unique INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE poubelle_connectee CHANGE id id_unique INT AUTO_INCREMENT NOT NULL');
        
        // 4. Réactiver les vérifications de clé étrangère
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
        
        // 5. Recréer la contrainte de clé étrangère avec les nouvelles colonnes
        $this->addSql('ALTER TABLE poubelle_connectee ADD CONSTRAINT FK_7D9DDF9F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id_unique) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // 1. Supprimer d'abord la contrainte de clé étrangère
        $this->addSql('ALTER TABLE poubelle_connectee DROP FOREIGN KEY FK_7D9DDF9F520CF5A');
        
        // 2. Désactiver les vérifications de clé étrangère temporairement
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');
        
        // 3. Renommer les colonnes id_unique en id
        $this->addSql('ALTER TABLE objet_connecte CHANGE id_unique id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE poubelle_connectee CHANGE id_unique id INT AUTO_INCREMENT NOT NULL');
        
        // 4. Réactiver les vérifications de clé étrangère
        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
        
        // 5. Recréer la contrainte de clé étrangère avec les anciennes colonnes
        $this->addSql('ALTER TABLE poubelle_connectee ADD CONSTRAINT FK_7D9DDF9F520CF5A FOREIGN KEY (objet_id) REFERENCES objet_connecte (id) ON DELETE CASCADE');
    }
}
