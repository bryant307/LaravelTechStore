<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Family;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;

class CompleteCartWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_cart_workflow()
    {
        // Seed la base de datos
        $this->seed();
        
        //OBTENER UN PRODUCTO PARA LAS PRUEBAS
        $product = Product::first();
        
        if (!$product) {
            $this->markTestSkipped('No products found in database');
        }

        $response = $this->get(route('products.show', $product));
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('Agregar al carrito');

        // 2. Test adding product to cart via Livewire component
        Livewire::test('add-to-cart', ['product' => $product])
            ->set('quantity', 2)
            ->call('addToCart')
            ->assertHasNoErrors()
            ->assertSessionHas('message');

        // 3. Verify cart was created and product was added
        $cart = Cart::getCurrent();
        $this->assertCount(1, $cart->items);
        $this->assertEquals(2, $cart->items->first()->quantity);
        $this->assertEquals($product->id, $cart->items->first()->product_id);

        // 4. Test cart page displays the item
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee($product->name);

        
        Livewire::test('cart-icon')
            ->assertSet('count', 2);

     
        $cartDetail = Livewire::test('cart-detail');
        $cartDetail->assertSee($product->name);
   
        $cartItem = $cart->items->first();
        $cartDetail->call('removeItem', $cartItem->id);
        
        $cart->refresh();
        $this->assertCount(0, $cart->items);

       
        $response = $this->get(route('cart.index'));
        $response->assertSee('Tu carrito está vacío');
    }

    public function test_navigation_works_with_seeded_data()
    {
        $this->seed();
        
        // Test para la navegación
        $family = Family::first();
        
        if ($family) {
            Livewire::test('navigation')
                ->assertSet('family_id', $family->id)
                ->assertHasNoErrors();
        }
    }

    public function test_notification_system_works()
    {
        // Test notification component
        Livewire::test('notifications')
            ->dispatch('showNotification', message: 'Test message', type: 'success')
            ->assertSee('Test message');
    }
}
