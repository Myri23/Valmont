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
        
        // Créer le répertoire de sauvegarde s'il n'existe pas
        $filesystem = new Filesystem();
        if (!$filesystem->exists($this->backupDir)) {
            $filesystem->mkdir($this->backupDir);
        }
        
        // Analyser l'URL de la base de données
        $dbParams = $this->parseDatabaseUrl($this->databaseUrl);
        
        // Nom du fichier de sauvegarde
        $date = new \DateTime();
        $filename = sprintf(
            'backup_%s_%s.sql',
            $dbParams['dbname'],
            $date->format('Y-m-d_H-i-s')
        );
        $backupPath = $this->backupDir . '/' . $filename;
        
        try {
            // Utiliser la commande mysqldump (plus efficace et fiable pour les connexions distantes)
            $io->text("Préparation de la sauvegarde via mysqldump...");
            
            // Construction de la commande mysqldump avec les bons paramètres
            // Ajout du port si présent dans l'URL
            $port = isset($params['port']) ? $params['port'] : '3306';
            
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --opt --databases %s > %s',
                escapeshellarg($dbParams['host']),
                escapeshellarg($port),
                escapeshellarg($dbParams['user']),
                escapeshellarg($dbParams['password']),
                escapeshellarg($dbParams['dbname']),
                escapeshellarg($backupPath)
            );
            
            // Si on est sur Windows, ajuster la commande en conséquence
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $command = 'cmd /c ' . $command;
            }
            
            $process = Process::fromShellCommandline($command);
            $process->setTimeout(1800); // 30 minutes maximum
            
            $io->text("Exécution de la sauvegarde...");
            $process->run();
            
            if (!$process->isSuccessful()) {
                throw new \Exception($process->getErrorOutput() ?: "Erreur lors de l'exécution de mysqldump");
            }
            
            $io->success('Sauvegarde terminée avec succès: ' . $backupPath);
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $io->error('Erreur lors de la sauvegarde: ' . $e->getMessage());
            // Si le fichier a été créé mais est incomplet, on peut le supprimer
            if (file_exists($backupPath) && filesize($backupPath) == 0) {
                $filesystem->remove($backupPath);
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
