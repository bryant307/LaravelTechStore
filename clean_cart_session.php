<?php

// Este script limpia la sesión del carrito para evitar problemas de serialización
require __DIR__ . '/vendor/autoload.php';

// Inicializar Laravel para tener acceso a las clases principales
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Limpiar la sesión del carrito
\Illuminate\Support\Facades\Session::forget('cart');
echo "La sesión del carrito ha sido limpiada.\n";
