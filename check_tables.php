<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Comprobar si las tablas existen
    $tablasCarts = DB::select("SHOW TABLES LIKE 'carts'");
    $tablasCartItems = DB::select("SHOW TABLES LIKE 'cart_items'");
    $tablaCovers = DB::select("SHOW TABLES LIKE 'covers'");

    echo "Tabla carts: " . (count($tablasCarts) > 0 ? "Existe\n" : "No existe\n");
    echo "Tabla cart_items: " . (count($tablasCartItems) > 0 ? "Existe\n" : "No existe\n");
    echo "Tabla covers: " . (count($tablaCovers) > 0 ? "Existe\n" : "No existe\n");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
