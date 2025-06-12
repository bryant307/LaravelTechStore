<?php

namespace App\Livewire;

use Livewire\Component;

class ProductReviews extends Component
{
    public $product;
    public $rating = 5;
    public $title = '';
    public $comment = '';
    public $showReviewForm = false;
    
    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'nullable|string|max:255',
        'comment' => 'required|string|min:10',
    ];
    
    public function mount($product)
    {
        $this->product = $product;
    }
    
    public function toggleReviewForm()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para escribir una reseña');
        }
        
        $this->showReviewForm = !$this->showReviewForm;
    }
    
    public function submitReview()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para escribir una reseña');
        }
        
        $this->validate();
        
        // Verificar si el usuario ya ha dejado una reseña para este producto
        $existingReview = $this->product->reviews()->where('user_id', auth()->id())->first();
        
        if ($existingReview) {
            session()->flash('error', 'Ya has escrito una reseña para este producto');
            return;
        }
        
        // Crear la reseña
        auth()->user()->reviews()->create([
            'product_id' => $this->product->id,
            'rating' => $this->rating,
            'title' => $this->title,
            'comment' => $this->comment,
            'verified_purchase' => false, // Implementar lógica para verificar compra
            'approved' => true, // Por defecto aprobada
        ]);
        
        // Limpiar el formulario y cerrarlo
        $this->reset(['rating', 'title', 'comment', 'showReviewForm']);
        
        // Mostrar mensaje de éxito
        session()->flash('success', '¡Gracias por tu reseña!');
        
        // Recargar la página para mostrar la nueva reseña
        $this->dispatch('review-added');
    }
    
    /**
     * Elimina una reseña desde el componente Livewire
     */
    public function deleteReview($reviewId)
    {
        // Buscar la reseña
        $review = \App\Models\Review::find($reviewId);
        
        if (!$review) {
            session()->flash('error', 'La reseña no existe o ya ha sido eliminada');
            return;
        }
        
        // Verificar que el usuario tenga permiso para eliminarla
        if (auth()->id() != $review->user_id && !auth()->user()->isAdmin()) {
            session()->flash('error', 'No tienes permiso para eliminar esta reseña');
            return;
        }
        
        // Eliminar la reseña
        $review->delete();
        
        // Mostrar mensaje de éxito
        session()->flash('success', 'La reseña ha sido eliminada correctamente');
    }
    
    public function render()
    {
        // Obtener todas las reseñas aprobadas del producto, ordenadas por fecha
        $reviews = $this->product->reviews()
            ->where('approved', true)
            ->with('user')
            ->latest()
            ->paginate(5);
        
        return view('livewire.product-reviews', [
            'reviews' => $reviews,
            'averageRating' => $this->product->average_rating,
            'totalReviews' => $this->product->reviews_count
        ]);
    }
}
