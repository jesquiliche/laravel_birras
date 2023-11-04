<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore {file : The path to the backup file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a database from a backup file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $backupFile = $this->argument('file');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $backupPath = base_path($backupFile);

        $command = "mysql -u $username -p$password -h $host -P $port $database < $backupPath";

        // Usar exec para ejecutar el comando
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info('Database restored successfully.');
        } else {
            $this->error('Database restore failed.');
        }
    }
}
