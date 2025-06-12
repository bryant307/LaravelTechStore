<div>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filtros (Sidebar) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Filtros</h2>
                    @if(!empty($selectedFeatures))
                        <button wire:click="clearFilters" 
                                class="text-sm text-red-600 hover:text-red-800 underline">
                            Limpiar
                        </button>
                    @endif
                </div>
                
                @if($options && $options->count() > 0)
                    @foreach($options as $option)
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-3">{{ $option->name }}</h3>
                            
                            @if($option->features && $option->features->count() > 0)
                                <div class="space-y-2">
                                    @foreach($option->features as $feature)
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                wire:model.live="selectedFeatures"
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                value="{{ $feature->id }}">
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ $feature->value }}
                                                @if($feature->description)
                                                    <span class="text-gray-500">({{ $feature->description }})</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No hay características disponibles para {{ $option->name }}</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-gray-500 p-2 bg-gray-50 rounded">
                        <p>No hay opciones de filtro disponibles para esta familia.</p>
                    </div>
                @endif
                
                <!-- Filtros activos -->
                @if($category_id && !$subcategory_id)
                    <div class="mt-6 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-yellow-800 font-medium">Categoría seleccionada:</p>
                                <p class="text-yellow-700">{{ $category_name ?? 'Categoría #'.$category_id }}</p>
                            </div>
                            <button wire:click="clearCategory" 
                                    class="text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if($subcategory_id)
                    <div class="mt-6 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-800 font-medium">Subcategoría seleccionada:</p>
                                <p class="text-green-700">{{ $subcategory_name ?? 'Subcategoría #'.$subcategory_id }}</p>
                            </div>
                            <button wire:click="clearSubcategory" 
                                    class="text-green-600 hover:text-green-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
                
                <!-- Current Search Term -->
                @if(!empty($search))
                    <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-800 font-medium">Buscando:</p>
                                <p class="text-blue-700">"{{ $search }}"</p>
                            </div>
                            <button wire:click="$set('search', '')" 
                                    class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Contenido principal - Productos -->
        <div class="lg:col-span-3">
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Productos</h2>
                        <span class="text-gray-600">
                            {{ $products->count() }} producto(s) encontrado(s)
                            @if(!empty($search))
                                <span class="text-blue-600">para "{{ $search }}"</span>
                            @endif
                            @if($category_id && !$subcategory_id)
                                <span class="text-yellow-600">en la categoría {{ $category_name }}</span>
                            @endif
                            @if($subcategory_id)
                                <span class="text-green-600">en la subcategoría {{ $subcategory_name }}</span>
                            @endif
                        </span>
                    </div>
                    
                    <!-- Sorting Controls -->
                    <div class="flex items-center gap-2">
                        <label for="sortBy" class="text-sm font-medium text-gray-700">Ordenar por:</label>
                        <select wire:model.live="sortBy" 
                                id="sortBy"
                                class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="relevance">Relevancia</option>
                            <option value="price_asc">Precio: Menor a Mayor</option>
                            <option value="price_desc">Precio: Mayor a Menor</option>
                            <option value="name_asc">Nombre: A-Z</option>
                            <option value="newest">Más Recientes</option>
                        </select>
                    </div>
                </div>
                
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                @if($product->image_path)
                                    <img src="{{ $product->image }}" 
                                        alt="{{ $product->name }}" 
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500">Sin imagen</span>
                                    </div>
                                @endif
                                
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $product->description }}</p>
                                    <p class="text-blue-600 font-bold text-lg">${{ number_format($product->price, 2) }}</p>
                                    
                                    @if($product->subcategory)
                                        <p class="text-gray-500 text-xs mt-2">
                                            {{ $product->subcategory->category->name }} > {{ $product->subcategory->name }}
                                        </p>
                                    @endif
                                    
                                    @if($product->options && $product->options->count() > 0)
                                        <div class="mt-3">
                                            <p class="text-xs text-gray-600 mb-1">Características:</p>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($product->options->take(3) as $option)
                                                    <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                                        {{ $option->name }}: {{ $option->pivot->value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('products.show', $product) }}" 
                                           class="block w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors text-center">
                                            Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4-4-4m0 0L8 8l4-4 4 4z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-lg">No se encontraron productos con los filtros seleccionados.</p>
                        @if(!empty($selectedFeatures))
                            <button wire:click="clearFilters" 
                                    class="mt-4 text-blue-600 hover:text-blue-800 underline">
                                Limpiar todos los filtros
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
