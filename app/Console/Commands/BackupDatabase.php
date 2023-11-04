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
        $filename = 'backup-' . date('d-m-Y-His') . '.sql';

        // Crea un nuevo proceso para ejecutar mysqldump
        $process = new Process([
            'mysqldump',
        //    '--routines',
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
            $this->info('Backup creado correctamente: ' . $filename);
        } else {
            $this->error('El backup fallo: ' . $process->getErrorOutput());
        }

        // Obtiene la salida del proceso
        $output = $process->getOutput();
        echo $output;
        // Obtén la ruta base de tu proyecto
        $basePath = base_path();

        // Define la ruta relativa a la carpeta de respaldo
        $relativePath = 'app/backup/' . $filename;

        // Combina la ruta base con la ruta relativa
        $file_path = $basePath . '/' . $relativePath;
        echo $file_path;

        // Guarda la salida del proceso en el archivo
        file_put_contents($file_path, $output);
    }
}
