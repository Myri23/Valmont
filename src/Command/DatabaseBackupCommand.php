<?php
// src/Command/DatabaseBackupCommand.php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:database:backup',
    description: 'Sauvegarde la base de données dans un fichier SQL'
)]
class DatabaseBackupCommand extends Command
{
    private $databaseUrl;
    private $backupDir;

    public function __construct(string $databaseUrl, string $projectDir)
    {
        $this->databaseUrl = $databaseUrl;
        $this->backupDir = $projectDir . '/var/backups';

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Crée une sauvegarde de la base de données.');
    }

protected function execute(InputInterface $input, OutputInterface $output): int
{
    $io = new SymfonyStyle($input, $output);
    
    // Créer le répertoire
    $filesystem = new Filesystem();
    if (!$filesystem->exists($this->backupDir)) {
        $filesystem->mkdir($this->backupDir);
    }
    
    // Analyser l'URL
    $dbParams = $this->parseDatabaseUrl($this->databaseUrl);
    
    // Nom du fichier
    $date = new \DateTime();
    $filename = sprintf(
        'backup_%s_%s.sql',
        $dbParams['dbname'],
        $date->format('Y-m-d_H-i-s')
    );
    $backupPath = $this->backupDir . '/' . $filename;
    
    try {
        // Se connecter avec timeout augmenté
        $dsn = sprintf(
            '%s:host=%s;dbname=%s;port=%s;connect_timeout=60',
            $dbParams['driver'],
            $dbParams['host'],
            $dbParams['dbname'],
            $dbParams['port']
        );
        
        $io->text("Connexion à la base de données...");
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_TIMEOUT => 60, // 60 secondes timeout
        ];
        $pdo = new \PDO($dsn, $dbParams['user'], $dbParams['password'], $options);
        
        // Récupérer la liste des tables
        $io->text("Récupération de la liste des tables...");
        $tables = [];
        $stmt = $pdo->query('SHOW TABLES');
        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        
        if (empty($tables)) {
            $io->warning('Aucune table trouvée');
            return Command::SUCCESS;
        }
        
        // Ouvrir le fichier
        $io->text("Création du fichier de sauvegarde...");

        // Ajout sécurité
        $file = fopen($backupPath, 'w');
        if ($file === false) {
            $io->error('Impossible de créer le fichier de sauvegarde.');
            return Command::FAILURE;
        }

        
        // En-tête
        fwrite($file, "-- Sauvegarde de {$dbParams['dbname']}\n");
        fwrite($file, "-- Date: " . date('Y-m-d H:i:s') . "\n\n");
        fwrite($file, "SET FOREIGN_KEY_CHECKS = 0;\n\n");
        
        // Traiter chaque table individuellement
        foreach ($tables as $table) {
            $io->text("Sauvegarde de la structure: $table");
            
            // Structure de la table
            fwrite($file, "-- Structure de la table `$table`\n");
            fwrite($file, "DROP TABLE IF EXISTS `$table`;\n");
            
            try {
                $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
                $row = $stmt->fetch(\PDO::FETCH_NUM);
                fwrite($file, $row[1] . ";\n\n");
            } catch (\Exception $e) {
                $io->warning("Erreur lors de la récupération de la structure pour $table: " . $e->getMessage());
                continue; // Passer à la table suivante
            }
            
            // Données de la table - Traitement par lots
            $io->text("Sauvegarde des données: $table");
            fwrite($file, "-- Données de la table `$table`\n");
            
            try {
                // Compter le nombre d'enregistrements
                $countStmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
                $totalRows = $countStmt->fetchColumn();
                
                if ($totalRows > 0) {
                    // Récupérer les colonnes
                    $sampleStmt = $pdo->query("SELECT * FROM `$table` LIMIT 1");
                    $columns = [];
                    for ($i = 0; $i < $sampleStmt->columnCount(); $i++) {
                        $meta = $sampleStmt->getColumnMeta($i);
                        $columns[] = $meta['name'];
                    }
                    $columnList = '`' . implode('`, `', $columns) . '`';
                    
                    // Traiter par lots de 500 lignes
                    $batchSize = 500;
                    $offset = 0;
                    
                    while ($offset < $totalRows) {
                        $io->text("  - Traitement des lignes $offset à " . min($offset + $batchSize, $totalRows) . " / $totalRows");
                        
                        $batchStmt = $pdo->query("SELECT * FROM `$table` LIMIT $offset, $batchSize");
                        $rowCount = 0;
                        
                        while ($row = $batchStmt->fetch(\PDO::FETCH_ASSOC)) {
                            if ($rowCount === 0) {
                                fwrite($file, "INSERT INTO `$table` ($columnList) VALUES\n");
                            } else {
                                fwrite($file, ",\n");
                            }
                            
                            $values = array_map(function($value) use ($pdo) {
                                if ($value === null) {
                                    return 'NULL';
                                }
                                return $pdo->quote($value);
                            }, array_values($row));
                            
                            fwrite($file, "(" . implode(", ", $values) . ")");
                            $rowCount++;
                        }
                        
                        if ($rowCount > 0) {
                            fwrite($file, ";\n");
                        }
                        
                        $offset += $batchSize;
                        
                        // Reconnecter si nécessaire
                        if ($offset < $totalRows) {
                            try {
                                $pdo->query("SELECT 1");
                            } catch (\Exception $e) {
                                $io->warning("Reconnexion à la base de données...");
                                $pdo = new \PDO($dsn, $dbParams['user'], $dbParams['password'], $options);
                            }
                        }
                    }
                }
                
                fwrite($file, "\n");
                
            } catch (\Exception $e) {
                $io->warning("Erreur lors de la sauvegarde des données de $table: " . $e->getMessage());
                // Continuer avec la table suivante
            }
        }
        
        fwrite($file, "SET FOREIGN_KEY_CHECKS = 1;\n");
        fclose($file);
        $io->success('Sauvegarde terminée: ' . $backupPath);
        return Command::SUCCESS;
        
    } catch (\Exception $e) {
        $io->error('Erreur: ' . $e->getMessage());
        if (isset($file) && is_resource($file)) {
            fclose($file);
        }
        return Command::FAILURE;
    }
}
    
    private function parseDatabaseUrl(string $url): array
    {
        $params = parse_url($url);
        // Récupérer le port s'il existe
        $port = isset($params['port']) ? $params['port'] : '3306';
        
        return [
            'driver' => isset($params['scheme']) ? $params['scheme'] : 'mysql',
            'user' => isset($params['user']) ? $params['user'] : '',
            'password' => isset($params['pass']) ? $params['pass'] : '',
            'host' => isset($params['host']) ? $params['host'] : 'localhost',
            'port' => $port,
            'dbname' => isset($params['path']) ? substr($params['path'], 1) : '',
        ];
    }
}
