<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Exception;

class CheckoutController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        // No necesitamos aplicar middleware aquí, se hace en las rutas
    }

    /**
     * Mostrar la página de opciones de pago
     */
    public function showPaymentOptions()
    {
        // Verificar si hay una dirección de envío seleccionada
        $shippingAddressId = session('shipping_address_id');
        
        if (!$shippingAddressId) {
            session()->flash('error', 'Debes seleccionar una dirección de envío antes de continuar al pago.');
            return redirect()->route('shipping.index');
        }
        
        $address = Address::find($shippingAddressId);
        
        // Verificar que la dirección pertenezca al usuario actual
        if (!$address || $address->user_id !== Auth::id()) {
            session()->flash('error', 'La dirección seleccionada no es válida.');
            return redirect()->route('shipping.index');
        }
        
        // Crear un intento de pago de Stripe si se va a pagar con tarjeta
        $intent = null;
        try {
            // Calcular el monto en centavos para Stripe
            $cart = session('cart');
            if ($cart && !$cart->items->isEmpty()) {
                $amount = $cart->total * 100; // Stripe trabaja con centavos
                
                // Inicializar Stripe con la clave secreta
                Stripe::setApiKey(config('services.stripe.secret'));
                
                // Crear un intento de pago
                $intent = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'pen', // Moneda peruana: soles
                    'metadata' => [
                        'user_id' => Auth::id(),
                        'address_id' => $address->id,
                    ],
                ]);
            }
        } catch (Exception $e) {
            // Manejar errores de Stripe
            report($e);
            // Si hay un error, continuamos sin crear el PaymentIntent
        }
        
        return view('checkout.payment', [
            'address' => $address,
            'clientSecret' => $intent ? $intent->client_secret : null
        ]);
    }

    /**
     * Procesar el pago y completar el pedido
     */
    public function processPayment(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'payment_method' => 'required|in:credit_card,cash',
            'payment_method_id' => 'required_if:payment_method,credit_card',
        ]);
        
        // Verificar que el usuario tenga un carrito activo
        $cart = session('cart');
        if (!$cart || $cart->items->isEmpty()) {
            session()->flash('error', 'Tu carrito está vacío.');
            return redirect()->route('cart.index');
        }
        
        try {
            if ($request->payment_method === 'credit_card') {
                // Procesar el pago con Stripe
                Stripe::setApiKey(config('services.stripe.secret'));
                
                // Crear un intento de pago con el método de pago proporcionado
                $paymentIntent = PaymentIntent::create([
                    'amount' => $cart->total * 100,
                    'currency' => 'pen',
                    'payment_method' => $request->payment_method_id,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                ]);
                
                // Verificar el estado del pago
                if ($paymentIntent->status !== 'succeeded') {
                    throw new Exception('El pago no pudo ser procesado.');
                }
                
                // Guardar la información del pago en la sesión
                session()->put('payment_intent_id', $paymentIntent->id);
            }
            
            // Guardar el método de pago en la sesión
            session()->put('payment_method', $request->payment_method);
              // Crear el pedido en la base de datos
            $shippingAddressId = session('shipping_address_id');
            $address = Address::findOrFail($shippingAddressId);
            
            // Crear un nuevo pedido
            $order = new Order();
            $order->user_id = Auth::id();
            $order->address_id = $address->id;
            $order->order_number = Order::generateOrderNumber();
            $order->total_amount = $cart->total;
            $order->payment_method = $request->payment_method;
            
            if ($request->payment_method === 'credit_card') {
                $order->payment_id = $paymentIntent->id;
                $order->status = 'processing'; // Pagado, listo para procesar
            } else {
                $order->status = 'pending'; // Pendiente de pago (contra entrega)
            }
            
            $order->save();
            
            // Guardar los items del pedido
            foreach ($cart->items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->product_variant_id = $item->options['variant_id'] ?? null;
                $orderItem->name = $item->name;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->qty;
                $orderItem->options = $item->options;
                $orderItem->subtotal = $item->price * $item->qty;
                $orderItem->save();
                
                // Actualizar el inventario
                $this->updateInventory($item->id, $item->options['variant_id'] ?? null, $item->qty);
            }
            
            // Vaciar el carrito después de crear el pedido
            $cart->clear();
            
            // Guardar el ID del pedido en la sesión para mostrarlo en la confirmación
            session()->put('order_id', $order->id);
            
            return view('checkout.confirmation', ['order' => $order]);
            
        } catch (ApiErrorException $e) {
            // Manejar errores específicos de Stripe
            session()->flash('error', 'Error al procesar el pago: ' . $e->getMessage());
            return back();        } catch (Exception $e) {
            // Manejar otros errores
            session()->flash('error', 'Ocurrió un error al procesar tu pedido: ' . $e->getMessage());
            return back();
        }
    }
    
    /**
     * Actualiza el inventario después de una compra
     *
     * @param int $productId ID del producto
     * @param int|null $variantId ID de la variante (opcional)
     * @param int $quantity Cantidad comprada
     * @return void
     */
    private function updateInventory($productId, $variantId = null, $quantity = 1)
    {
        if ($variantId) {
            // Si existe una variante específica, actualizar su stock
            $variant = ProductVariant::find($variantId);
            if ($variant && $variant->track_inventory) {
                $variant->stock = max(0, $variant->stock - $quantity);
                $variant->save();
            }
        } else {
            // Si no hay variante, actualizar el stock del producto principal
            $product = Product::find($productId);
            if ($product && $product->track_inventory) {
                $product->stock = max(0, $product->stock - $quantity);
                $product->save();
            }
        }
    }
}
