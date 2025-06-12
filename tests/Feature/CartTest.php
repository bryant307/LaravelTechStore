<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_current_cart_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $cart = Cart::getCurrent();

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertEquals($user->id, $cart->user_id);
        $this->assertNull($cart->session_id);
    }    public function test_can_get_current_cart_for_guest_user()
    {
        // Iniciar sesión de Laravel
        $this->startSession();
        
        // Simular una sesión personalizada para el carrito
        session()->put('cart_session_id', 'test-session-123');

        $cart = Cart::getCurrent();

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertNull($cart->user_id);
        $this->assertEquals('test-session-123', $cart->session_id);
    }

    public function test_product_detail_page_loads_successfully()
    {
        $this->seed();
        
        $product = Product::first();
        
        if ($product) {
            $response = $this->get(route('products.show', $product));
            $response->assertStatus(200);
            $response->assertSee($product->name);
            $response->assertSee('Agregar al carrito');
        } else {
            $this->markTestSkipped('No products found in database');
        }
    }    public function test_cart_page_loads_successfully()
    {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee('Mi Carrito');
    }
}
