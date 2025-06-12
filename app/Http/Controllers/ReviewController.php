<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Crear una nueva reseña para un producto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10',
        ]);
        
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para escribir una reseña');
        }
        
        // Crear la reseña
        $review = auth()->user()->reviews()->create([
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'verified_purchase' => false, // Implementar lógica para verificar compra
            'approved' => true, // Por defecto aprobada, se podría modificar según necesidades
        ]);
        
        return redirect()->back()->with('success', 'Tu reseña ha sido publicada. ¡Gracias por compartir tu opinión!');
    }
    
    /**
     * Eliminar una reseña
     * 
     * Nota: Esta función ya no se usa ya que ahora el borrado de reseñas
     * se maneja directamente en el componente Livewire ProductReviews
     */
    public function destroy(\App\Models\Review $review)
    {
        // Verificar que el usuario sea el propietario de la reseña o un administrador
        if (auth()->id() !== $review->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $review->delete();
        
        return redirect()->back()->with('success', 'La reseña ha sido eliminada');
    }
}
