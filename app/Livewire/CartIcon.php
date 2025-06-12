<?php

namespace App\Livewire;

use App\Models\Cart;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class CartIcon extends Component
{
    public $count = 0;

    protected $listeners = ['product-added-to-cart' => 'updateCartCount'];
    

    // Este método se ejecuta al montar el componente
    // y se usa para inicializar el conteo del carrito
    public function mount()
    {
        $this->updateCartCount();
    }    // Este método se usa para actualizar el conteo del carrito
    // cuando se recibe el evento 'product-added-to-cart'
    public function updateCartCount($data = null)
    {
        try {
            if ($data && isset($data['cart_count'])) {
                $this->count = $data['cart_count'];
            } else {
                $cart = Cart::getCurrent();
                $this->count = $cart ? $cart->items_count : 0;
            }
        } catch (\Exception $e) {
            // En caso de error, establecer el contador en 0
            $this->count = 0;
        }
    }
    // Este método se usa para redirigir al usuario a la página del carrito
    public function render()
    {
        return view('livewire.cart-icon');
    }
}
