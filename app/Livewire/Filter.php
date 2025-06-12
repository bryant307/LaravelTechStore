<?php

namespace App\Livewire;

use App\Models\Family;
use App\Models\Product;
use App\Models\Option;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Filter extends Component
{
    public $family_id;
    public $options;
    public $search;
    public $selectedFeatures = [];
    public $products = [];
    public $sortBy = 'relevance'; //Para ordenar por relevancia por defecto
    public $subcategory_id = null; // Para filtrar por subcategoría
    public $subcategory_name = null; // Para mostrar el nombre de la subcategoría
    public $category_id = null; // Para filtrar por categoría
    public $category_name = null; // Para mostrar el nombre de la categoría
    public function mount($family_id = null, $options = null, $subcategory_id = null, $category_id = null, $search = null) // family_id puede ser nulo en la página de búsqueda
    {
        Log::info('Filter Component - Mount with params:', [
            'family_id' => $family_id,
            'category_id' => $category_id,
            'subcategory_id' => $subcategory_id,
            'search' => $search
        ]);
        
        // Inicializa la búsqueda si se proporciona
        if ($search) {
            $this->search = $search;
        }
        
        // En la página de búsqueda, family_id puede ser nulo
        $this->family_id = $family_id;
        
        // Si se proporcionó un ID de subcategoría, lo establecemos
        if ($subcategory_id) {
            $this->subcategory_id = (int) $subcategory_id;
            // Obtenemos el nombre de la subcategoría
            $subcategory = \App\Models\Subcategory::find($this->subcategory_id);
            if ($subcategory) {
                $this->subcategory_name = $subcategory->name;
                Log::info('Mounted with subcategory: ' . $this->subcategory_name);
            }
        }
        
        // Si se proporcionó un ID de categoría, lo establecemos
        if ($category_id) {
            $this->category_id = (int) $category_id;
            // Obtenemos el nombre de la categoría
            $category = \App\Models\Category::find($this->category_id);
            if ($category) {
                $this->category_name = $category->name;
                Log::info('Mounted with category: ' . $this->category_name);
            }
        }
        
        if ($options) {
            $this->options = $options;
        } else {
            $this->loadOptions();
        }
        
        $this->loadProducts();
    }

    public function loadOptions()
    {
        if ($this->family_id) {
            // Si hay un family_id, cargar opciones para esa familia
            $this->options = Option::byFamily($this->family_id)->with('features')->get();
            
            // Si no hay opciones con esa relación específica, cargar todas las opciones
            if ($this->options->isEmpty()) {
                $this->options = Option::with('features')->get();
            }
        } else {
            // Si no hay family_id (como en una página de búsqueda), cargar todas las opciones
            $this->options = Option::with('features')->get();
        }
    }

    public function updatedSelectedFeatures()
    {
        $this->loadProducts();
    }

    public function updatedSortBy()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        Log::info('Loading products with filters:', [
            'family_id' => $this->family_id,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'search' => $this->search,
            'features' => $this->selectedFeatures
        ]);
        
        // Iniciar la consulta de productos
        if ($this->family_id) {
            // Si hay un family_id, filtrar por familia
            $query = Product::byFamily($this->family_id);
        } else {
            // Si no hay family_id (como en una página de búsqueda), iniciar una consulta general
            $query = Product::query();
        }
        
        // Aplicar filtro de categoría si está seleccionada
        if ($this->category_id) {
            Log::info('Applying category filter: ' . $this->category_id);
            $query->byCategory($this->category_id);
        }
        
        // Aplicar filtro de subcategoría si está seleccionada
        if ($this->subcategory_id) {
            Log::info('Applying subcategory filter: ' . $this->subcategory_id);
            $query->bySubcategory($this->subcategory_id);
        }
        
        // Aplicar filtro de búsqueda si hay un término
        if (!empty($this->search)) {
            Log::info('Searching for: ' . $this->search);
            $query->search($this->search);
        }
        
        // Aplicar filtro de características si hay seleccionadas
        if (!empty($this->selectedFeatures)) {
            Log::info('Applying feature filters: ' . implode(', ', $this->selectedFeatures));
            $query->byFeatures($this->selectedFeatures);
        }
        
        // Aplicar ordenamiento según la opción seleccionada
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'relevance':
            default:
                // Ordenar por relevancia, utilizando el query scope
                $query->orderByRelevance($this->search);
                break;
        }

        $this->products = $query->with(['subcategory.category', 'options.features'])->get();
    }


    #[On('search')]
    public function search($search)
    {
        // Handle both string and object format
        if (is_array($search) && isset($search['search'])) {
            $this->search = $search['search'] ?? '';
        } else {
            $this->search = $search ?? '';
        }
        
        Log::info('Search received: ' . $this->search);
        $this->loadProducts();
    }

    public function updatedSearch()
    {
        Log::info('Search updated: ' . $this->search);
        $this->loadProducts();
    }

    #[On('filter-by-category')]
    public function filterByCategory($category_id)
    {
        Log::info('Filter Component - Received filter-by-category event: ' . $category_id);
        
        // Convertir a entero si es necesario
        $category_id = (int) $category_id;
        
        $this->category_id = $category_id;
        $this->subcategory_id = null; // Reiniciar subcategoría cuando se selecciona una categoría
        $this->subcategory_name = null;
        
        // Buscar el nombre de la categoría
        $category = \App\Models\Category::find($category_id);
        if ($category) {
            $this->category_name = $category->name;
            Log::info('Category found: ' . $this->category_name);
        } else {
            Log::warning('Category not found with ID: ' . $category_id);
        }
        
        $this->loadProducts();
    }

    #[On('filter-by-subcategory')]
    public function filterBySubcategory($subcategory_id)
    {
        Log::info('Filter Component - Received filter-by-subcategory event: ' . $subcategory_id);
        
        // Convertir a entero si es necesario
        $subcategory_id = (int) $subcategory_id;
        
        $this->subcategory_id = $subcategory_id;
        
        // Buscar el nombre de la subcategoría
        $subcategory = \App\Models\Subcategory::find($subcategory_id);
        if ($subcategory) {
            $this->subcategory_name = $subcategory->name;
            // También actualizamos la categoría cuando se selecciona una subcategoría
            $this->category_id = $subcategory->category_id;
            $this->category_name = $subcategory->category->name;
            Log::info('Subcategory found: ' . $this->subcategory_name . ' in category: ' . $this->category_name);
        } else {
            Log::warning('Subcategory not found with ID: ' . $subcategory_id);
        }
        
        $this->loadProducts();
    }

    public function clearCategory()
    {
        $this->category_id = null;
        $this->category_name = null;
        $this->subcategory_id = null; // También limpiamos la subcategoría cuando se limpia la categoría
        $this->subcategory_name = null;
        $this->loadProducts();
    }

    public function clearSubcategory()
    {
        $this->subcategory_id = null;
        $this->subcategory_name = null;
        $this->loadProducts();
    }
    
    public function clearFilters()
    {
        $this->selectedFeatures = [];
        $this->search = '';
        $this->sortBy = 'relevance';
        $this->category_id = null;
        $this->category_name = null;
        $this->subcategory_id = null;
        $this->subcategory_name = null;
        $this->loadProducts();
        
        // Notificar que se han limpiado los filtros
        session()->flash('message', 'Todos los filtros han sido limpiados.');
    }

    public function render()
    {
        return view('livewire.filter');
    }
}
