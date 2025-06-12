<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckDbTables extends Command
{
    protected $signature = 'db:check-tables';
    protected $description = 'Verifica si existen tablas clave en la base de datos';

    public function handle()
    {
        $tables = ['carts', 'cart_items', 'covers', 'products', 'users'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->info("Tabla {$table}: Existe ({$count} registros)");
            } else {
                $this->error("Tabla {$table}: No existe");
            }
        }
        
        // Mostrar errores en las migraciones
        $failed = DB::table('migrations')->where('batch', '<', 0)->get();
        if ($failed->count() > 0) {
            $this->error("Migraciones fallidas:");
            foreach ($failed as $migration) {
                $this->error("- {$migration->migration}");
            }
        } else {
            $this->info("No hay migraciones fallidas");
        }
        
        // Mostrar migraciones ejecutadas
        $migrations = DB::table('migrations')->orderBy('batch')->get();
        $this->info("Migraciones ejecutadas:");
        foreach ($migrations as $migration) {
            $this->info("- Batch {$migration->batch}: {$migration->migration}");
        }
    }
}
