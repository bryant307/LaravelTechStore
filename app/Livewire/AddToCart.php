<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class AddToCart extends Component
{
    public Product $product;
    public int $quantity = 1;
    public $selectedVariants = [];
    public $variantIds = [];
    public $price;
    public $hasVariants = false;
    public $selectedVariantId = null;
    
    public function mount(Product $product)
    {
        $this->product = $product;
        $this->price = $product->price;
        
        // Verificar si el producto tiene variantes
        $this->hasVariants = $product->productVariants->count() > 0;
    }
    
    public function updatedSelectedVariants($value, $key)
    {
        // Si se selecciona una variante
        if ($value) {
            $variant = ProductVariant::find($value);
            if ($variant) {
                // Si la variante tiene un precio diferente, actualizar
                if ($variant->price) {
                    $this->price = $variant->price;
                }
                
                // Guardar el ID de la variante seleccionada
                $this->selectedVariantId = $variant->id;
                
                // Emitir un evento para que el componente de la galería actualice la imagen
                $this->dispatch('variant-selected', variantId: $variant->id);
            }
        }
    }
    
    public function increment()
    {
        $this->quantity++;
    }
    
    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }public function addToCart()
    {
        try {
            // Obtener el carrito actual (o crear uno nuevo)
            $cart = Cart::getCurrent();
            
            // Preparamos los datos adicionales para el carrito
            $additionalData = [
                'image' => $this->product->image,
                'slug' => $this->product->slug
            ];
            
            // Si tenemos una variante seleccionada, incluir los detalles
            $productName = $this->product->name;
            $productPrice = $this->price; // Usamos el precio que puede haber sido modificado al seleccionar variantes
            
            if ($this->selectedVariantId) {
                $variant = ProductVariant::find($this->selectedVariantId);
                if ($variant) {
                    // Incluir info de la variante en los datos adicionales
                    $additionalData['variant_id'] = $variant->id;
                    $additionalData['variant_value'] = $variant->value;
                    $additionalData['variant_option'] = $variant->option ? $variant->option->name : '';
                    
                    // Agregar información de variante al nombre del producto
                    $productName .= ' - ' . ($variant->option ? $variant->option->name : '') . ': ' . $variant->value;
                }
            }
            
            // Agregar el producto al carrito con la cantidad especificada
            $cart->add(
                $this->product->id,
                $productName,
                $productPrice,
                $this->quantity,
                $additionalData
            );
            
            // El total se actualiza automáticamente en el método add
            
            $message = 'Se ha agregado ' . $this->quantity . ' x ' . $productName . ' al carrito';
            
            // Mostrar el mensaje flash en el componente
            session()->flash('message', $message);
            
            // Disparar evento para mostrar una notificación global
            $this->dispatch('showNotification', 
                message: $message, 
                type: 'success'
            );
            
            // Restablecer la cantidad
            $this->quantity = 1;
            
            // Emitir evento para que otros componentes puedan responder (como actualizar el contador del carrito)
            $this->dispatch('product-added-to-cart', [
                'cart_count' => $cart->items_count
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al agregar producto al carrito: ' . $e->getMessage());
            
            $this->dispatch('showNotification', 
                message: 'Error al agregar el producto al carrito. Por favor, inténtelo de nuevo.', 
                type: 'error'
            );
        }
    }
    
    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
