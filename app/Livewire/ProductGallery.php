<?php

namespace App\Livewire;

use Livewire\Component;

class ProductGallery extends Component
{
    public $product;
    public $currentImage;
    public $currentVariantId = null;
    public $thumbnails = [];
    public $shareUrl;
    
    protected $listeners = ['variant-selected' => 'selectVariant'];
    
    public function mount($product)
    {
        $this->product = $product;
        $this->currentImage = $product->image;
        $this->loadProductImages();
        $this->shareUrl = url("/products/{$product->id}");
    }
    
    public function loadProductImages()
    {
        // Inicializar con la imagen principal del producto
        $this->thumbnails = [
            [
                'id' => 'main',
                'image' => $this->product->image,
                'is_current' => true
            ]
        ];
        
        // Obtener imágenes adicionales del producto (si las hay)
        // Esta parte puede expandirse si el modelo Product tiene una relación de imágenes
    }
    
    public function selectVariant($variantId)
    {
        $this->currentVariantId = $variantId;
        
        if ($variantId) {
            $variant = \App\Models\ProductVariant::with('images')->find($variantId);
            
            if ($variant) {
                // Actualizar la imagen principal
                $this->currentImage = $variant->primary_image ?: $this->product->image;
                
                // Cargar miniaturas de la variante
                $this->thumbnails = [];
                
                // Agregar imagen principal del producto como primera opción
                $this->thumbnails[] = [
                    'id' => 'main',
                    'image' => $this->product->image,
                    'is_current' => $this->currentImage === $this->product->image
                ];
                
                // Agregar imágenes de la variante
                foreach ($variant->images as $index => $image) {
                    $this->thumbnails[] = [
                        'id' => $image->id,
                        'image' => $image->image,
                        'is_current' => $this->currentImage === $image->image
                    ];
                    
                    // Si es la primera imagen y no hay imagen actual seleccionada, usarla como actual
                    if ($index === 0 && $this->currentImage === $this->product->image) {
                        $this->currentImage = $image->image;
                        $this->thumbnails[0]['is_current'] = false;
                        $this->thumbnails[1]['is_current'] = true;
                    }
                }
            }
        } else {
            // Volver a la imagen principal del producto
            $this->currentImage = $this->product->image;
            $this->loadProductImages();
        }
        
        $this->dispatch('variantImageChanged', $this->currentImage);
    }
    
    public function selectImage($imageId)
    {
        foreach ($this->thumbnails as &$thumbnail) {
            if ($thumbnail['id'] == $imageId) {
                $thumbnail['is_current'] = true;
                $this->currentImage = $thumbnail['image'];
            } else {
                $thumbnail['is_current'] = false;
            }
        }
        
        $this->dispatch('productImageChanged', $this->currentImage);
    }
    
    public function render()
    {
        return view('livewire.product-gallery');
    }
}
