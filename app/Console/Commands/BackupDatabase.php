<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database using mysqldump';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Genera un nombre de archivo para el respaldo basado en la fecha y hora
        $filename = 'backup-' . date('Y-m-d-His') . '.sql';

        // Crea un nuevo proceso para ejecutar mysqldump
        $process = new Process([
            'mysqldump',
            '-h' . config('database.connections.mysql.host'),
            '-P' . config('database.connections.mysql.port'),
            '-u' . config('database.connections.mysql.username'),
            '-p' . config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
        ]);

        // Ejecuta el proceso y redirige la salida al archivo
        $process->run();

        // Verifica si el proceso fue exitoso
        if ($process->isSuccessful()) {
            $this->info('Backup created successfully: ' . $filename);
        } else {
            $this->error('Backup failed: ' . $process->getErrorOutput());
        }

        // Obtiene la salida del proceso
        $output = $process->getOutput();

        // Ruta donde deseas guardar el archivo de respaldo
        $file_path = 'app/backup/' . $filename;

        // Guarda la salida del proceso en el archivo
        file_put_contents($file_path, $output);
    }
}

