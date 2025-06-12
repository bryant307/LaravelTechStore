<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Muestra el contenido del carrito del usuario.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cart = Cart::getCurrent();
        
        return view('cart.index', compact('cart'));
    }
    
    /**
     * Elimina todos los items del carrito.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        $cart = Cart::getCurrent();
        $cart->clear();
        
        return redirect()->route('cart.index')->with('success', 'Carrito vaciado correctamente.');
    }
}
