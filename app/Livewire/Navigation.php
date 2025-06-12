<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;

class Navigation extends Component
{
    public $families;

    public $family_id;

    public function mount()
    {
        $this->families = \App\Models\Family::all();
        $this->family_id = $this->families->first()?->id ?? null;
    }

    #[Computed()]
    public function categories()
    {
        if (!$this->family_id) {
            return collect();
        }
        
        return \App\Models\Category::where('family_id', $this->family_id)
            ->with('subcategories')
            ->get();
    }


    #[Computed()]
    public function familyName()
    {
        return \App\Models\Family::find($this->family_id)?->name ?? 'Sin categoría';
    }
    public function filterCategory($category_id)
    {
        // Obtenemos la categoría para tener más información
        $category = \App\Models\Category::find($category_id);
        
        if ($category) {
            \Illuminate\Support\Facades\Log::info('Navigation - Filtering by category: ' . $category_id);
        
            $this->dispatch('filter-by-category', category_id: $category_id);
            
            // Cerramos el sidebar
            $this->dispatch('close-sidebar');
            
            // Como alternativa, también podemos redirigir directamente
            return redirect()->route('categories.show', $category);
        }
    }
    
    public function filterSubcategory($subcategory_id)
    {
        // Obtenemos la subcategoría para tener más información
        $subcategory = \App\Models\Subcategory::find($subcategory_id);
        
        if ($subcategory) {
            \Illuminate\Support\Facades\Log::info('Navigation - Filtering by subcategory: ' . $subcategory_id);
            
            // Despachamos el evento para filtrar - asegurando que el parámetro sea correcto
            $this->dispatch('filter-by-subcategory', subcategory_id: $subcategory_id);
            
            // Cerramos el sidebar
            $this->dispatch('close-sidebar');
            
            // Como alternativa, también podemos redirigir directamente
            return redirect()->route('subcategories.show', $subcategory);
        }
    }
    
    /**
     * Método para manejar la búsqueda desde la barra de navegación
     * 
     * @param string $value El término de búsqueda ingresado por el usuario
     */
    public function search($value)
    {
        // Registramos la búsqueda para depuración
        \Illuminate\Support\Facades\Log::info('Navigation - Search term: ' . $value);
        
        // Redirigimos a la búsqueda si hay un término válido
        if (trim($value) !== '') {
            // Redirigir a la página de búsqueda dedicada
            return redirect()->route('search', ['q' => $value]);
        }
    }

    public function render()
    {
        return view('livewire.navigation', [
            'categories' => $this->categories
        ]);
    }
}
