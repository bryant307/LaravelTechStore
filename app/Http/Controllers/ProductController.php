<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{    /**
     * Muestra la página de detalles de un producto.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        // Cargamos las relaciones necesarias
        $product->load([
            'subcategory.category.family', 
            'options.features', 
            'productVariants.option'
        ]);
        
        // Agrupamos las variantes por opciones para mostrarlas organizadas
        $variantsByOption = $product->productVariants->groupBy('option_id');
        
        // Obtenemos productos relacionados de la misma subcategoría
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('subcategory_id', $product->subcategory_id)
            ->take(4)
            ->get();
        
        return view('products.show', compact('product', 'relatedProducts', 'variantsByOption'));
    }
}
