<?php

// Este script prueba la funcionalidad del carrito para verificar que funciona correctamente
require_once __DIR__ . '/vendor/autoload.php';

// Inicializar Laravel para tener acceso a las clases principales
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

try {
    // Limpiar el registro de errores y el carrito para empezar desde cero
    Log::info("==== INICIO DE LA PRUEBA DEL CARRITO ====");
    Session::forget('cart');

    // Crear un nuevo carrito
    $cart = Cart::getCurrent();
    echo "✓ Cart::getCurrent() funciona correctamente\n";

    // Agregar algunos productos
    echo "Agregando productos al carrito...\n";
    $cart->add(1, 'Producto de prueba 1', 19.99, 2, ['color' => 'rojo']);
    $cart->add(2, 'Producto de prueba 2', 29.99, 1, ['color' => 'azul']);
    echo "✓ Productos agregados correctamente\n";

    // Mostrar información del carrito
    echo "Contenido del carrito:\n";
    echo "✓ Número de productos: " . $cart->items_count . "\n";
    echo "✓ Total: $" . number_format($cart->total, 2) . "\n";

    // Guardar el carrito en la sesión
    Session::put('cart', $cart);

    // Recuperar el carrito de la sesión
    $retrievedCart = Cart::getCurrent();
    echo "\n✓ Carrito recuperado de la sesión correctamente\n";

    // Verificar que la recuperación funcionó
    echo "Verificando carrito recuperado:\n";
    echo "✓ Número de productos: " . $retrievedCart->items_count . "\n";
    echo "✓ Total: $" . number_format($retrievedCart->total, 2) . "\n";

    // Verificar las instancias de los items
    echo "\nVerificando instancias de CartItem:\n";
    foreach ($retrievedCart->items as $item) {
        echo "✓ " . get_class($item) . ": " . $item->name . " - Precio: $" . number_format($item->price, 2) . " - Cantidad: " . $item->qty . "\n";
    }

    echo "\n✓ Prueba completada correctamente!\n";
    Log::info("==== FIN DE LA PRUEBA DEL CARRITO ====");
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    Log::error("Error en prueba del carrito: " . $e->getMessage());
}
