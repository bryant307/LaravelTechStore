<?php

namespace App\Http\Controllers;

use App\Models\Cover;
use App\Models\Product;
use App\Models\Family;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $covers = Cover::where('is_active', true)
        ->whereDate('start_at', '<=', now())
        ->where(function ($query){
            $query->whereDate('end_at', '>=', now())
                  ->orWhereNull('end_at');
        })          
        ->get();
        
        $lastProducts = Product::latest()->take(12)->get();

        // Agregar familias que tienen productos para facilitar las pruebas
        $familiesWithProducts = Family::whereHas('categories.subcategories.products')->get();

        return view('welcome', compact('covers', 'lastProducts', 'familiesWithProducts'));
    }
}
