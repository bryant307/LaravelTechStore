<?php

namespace App\Livewire;

use App\Models\Cart;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class CartDetail extends Component
{
    // En lugar de almacenar todo el objeto Cart, vamos a usar propiedades simples
    public $cartItems = [];
    public $cartTotal = 0;
    public $cartItemsCount = 0;
    public $quantities = [];
    
    public function mount()
    {
        $this->refreshCart();
    }
      // Método para refrescar los datos del carrito
    protected function refreshCart()
    {
        try {
            Log::debug('CartDetail refreshCart: Iniciando recarga del carrito');
            $cart = Cart::getCurrent();
            
            // Convertir los items del carrito a un array simple
            $this->cartItems = [];
            
            // Guardar las cantidades antiguas para poder compararlas
            $oldQuantities = $this->quantities;
            $this->quantities = [];
            
            foreach ($cart->items as $item) {
                $this->cartItems[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'qty' => $item->qty,
                    'options' => $item->options
                ];
                
                // También actualizamos las cantidades
                $this->quantities[$item->id] = $item->qty;
                
                // Verificar si la cantidad cambió para depuración
                if (isset($oldQuantities[$item->id]) && $oldQuantities[$item->id] !== $item->qty) {
                    Log::debug("CartDetail refreshCart: Cantidad de producto ID:{$item->id} cambió de {$oldQuantities[$item->id]} a {$item->qty}");
                }
            }
            
            $this->cartTotal = $cart->total;
            $this->cartItemsCount = $cart->items_count;
            
            Log::debug('CartDetail refreshCart: Carrito recargado con éxito', [
                'itemsCount' => count($this->cartItems),
                'cartTotal' => $this->cartTotal,
                'cartItemsCount' => $this->cartItemsCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al refrescar el carrito: ' . $e->getMessage());
            $this->dispatch('showNotification', 
                message: 'Error al cargar el carrito. Por favor, recargue la página.', 
                type: 'error'
            );
        }
    }
      public function removeItem($itemId)
    {
        try {
            // Obtener el nombre del producto para el mensaje
            $productName = '';
            foreach ($this->cartItems as $item) {
                if ($item['id'] == $itemId) {
                    $productName = $item['name'];
                    break;
                }
            }
            
            // Obtener el carrito y eliminar el item
            $cart = Cart::getCurrent();
            $cart->remove($itemId);
            
            // Refrescar los datos del carrito
            $this->refreshCart();
            
            $this->dispatch('showNotification', 
                message: $productName . ' ha sido eliminado del carrito', 
                type: 'info'
            );
            
            // Actualizar el contador del carrito
            $this->dispatch('product-added-to-cart', [
                'cart_count' => $this->cartItemsCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar item del carrito: ' . $e->getMessage());
            $this->dispatch('showNotification', 
                message: 'Error al eliminar el producto. Por favor, inténtelo de nuevo.', 
                type: 'error'
            );
        }
    }
    
    public function updateQuantity($itemId)
    {
        try {
            $quantity = $this->quantities[$itemId];
            if ($quantity < 1) {
                $quantity = 1;
                $this->quantities[$itemId] = 1;
            }
            
            // Registrar el valor antes de la actualización para debug
            Log::info("Actualizando producto ID:{$itemId} a cantidad: {$quantity}");
            
            // Obtener el carrito y actualizar la cantidad
            $cart = Cart::getCurrent();
            $cart->update($itemId, $quantity);
            
            // Refrescar los datos del carrito
            $this->refreshCart();
            
            // Actualizar el contador del carrito
            $this->dispatch('product-added-to-cart', [
                'cart_count' => $this->cartItemsCount
            ]);
            
            // Mostrar notificación de éxito
            $this->dispatch('showNotification', 
                message: 'Cantidad actualizada correctamente', 
                type: 'success'
            );
        } catch (\Exception $e) {
            Log::error('Error al actualizar cantidad: ' . $e->getMessage());
            $this->dispatch('showNotification', 
                message: 'Error al actualizar la cantidad. Por favor, inténtelo de nuevo.', 
                type: 'error'
            );
        }
    }
    
    public function incrementQuantity($itemId)
    {
        try {
            // Si no existe el índice, inicializarlo en 1
            if (!isset($this->quantities[$itemId])) {
                $this->quantities[$itemId] = 1;
            }
            
            // Incrementar la cantidad
            $this->quantities[$itemId]++;
            
            // Actualizar en el carrito
            $this->updateQuantity($itemId);
            
        } catch (\Exception $e) {
            Log::error('Error al incrementar cantidad: ' . $e->getMessage());
            $this->dispatch('showNotification', 
                message: 'Error al incrementar la cantidad. Por favor, inténtelo de nuevo.', 
                type: 'error'
            );
        }
    }
    
    public function decrementQuantity($itemId)
    {
        try {
            // Si no existe el índice, inicializarlo en 1
            if (!isset($this->quantities[$itemId])) {
                $this->quantities[$itemId] = 1;
                return;
            }
            
            // Decrementar la cantidad pero no menos de 1
            if ($this->quantities[$itemId] > 1) {
                $this->quantities[$itemId]--;
                
                // Actualizar en el carrito
                $this->updateQuantity($itemId);
            }
            
        } catch (\Exception $e) {
            Log::error('Error al decrementar cantidad: ' . $e->getMessage());
            $this->dispatch('showNotification', 
                message: 'Error al decrementar la cantidad. Por favor, inténtelo de nuevo.', 
                type: 'error'
            );
        }
    }
    
    public function clearCart()
    {
        try {
            // Obtener el carrito y limpiarlo
            $cart = Cart::getCurrent();
            $cart->clear();
            
            // Refrescar los datos del carrito
            $this->refreshCart();
            
            $this->dispatch('showNotification', 
                message: 'El carrito ha sido vaciado', 
                type: 'info'
            );
            
            // Actualizar el contador del carrito
            $this->dispatch('product-added-to-cart', [
                'cart_count' => 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error al vaciar el carrito: ' . $e->getMessage());
            $this->dispatch('showNotification', 
                message: 'Error al vaciar el carrito. Por favor, inténtelo de nuevo.', 
                type: 'error'
            );
        }
    }
    public function render()
    {
        // Refrescar el carrito en cada renderizado para asegurar que tenemos los datos actualizados
        $this->refreshCart();
        
        Log::debug("CartDetail render: Items en el carrito: {$this->cartItemsCount}, Total: {$this->cartTotal}");
        
        // Debug de las cantidades
        Log::debug("CartDetail render: Estado actual de las cantidades:", $this->quantities);
        
        return view('livewire.cart-detail', [
            'cart' => (object)[
                'items' => collect($this->cartItems),
                'total' => $this->cartTotal,
                'items_count' => $this->cartItemsCount
            ]
        ]);
    }
}
