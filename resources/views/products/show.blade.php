@extends('layouts.app')

@section('content')
    <div class="py-8">
        <x-container>
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-blue-600">
                                <i class="fas fa-home mr-1"></i> Inicio
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                <a href="{{ route('families.show', $product->subcategory->category->family) }}" class="text-gray-700 hover:text-blue-600">
                                    {{ $product->subcategory->category->family->name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                <a href="{{ route('categories.show', $product->subcategory->category) }}" class="text-gray-700 hover:text-blue-600">
                                    {{ $product->subcategory->category->name }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="mx-2 text-gray-400">/</span>
                                <a href="{{ route('subcategories.show', $product->subcategory) }}" class="text-gray-700 hover:text-blue-600">
                                    {{ $product->subcategory->name }}
                                </a>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <!-- Contenido del producto -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Galería de imágenes del producto -->
                    <div>
                        @livewire('product-gallery', ['product' => $product])
                    </div>
                    
                    <!-- Detalles del producto -->
                    <div>
                        <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                        <p class="text-gray-600 mb-6">{{ $product->description }}</p>
                        
                        <!-- SKU -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                        </div>
                        
                        <!-- Categoría y subcategoría -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500">
                                Categoría: {{ $product->subcategory->category->name }} > {{ $product->subcategory->name }}
                            </p>
                        </div>
                          <!-- El precio ahora se maneja dinámicamente desde el componente Livewire -->
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                            @if($product->productVariants->count() > 0)
                                <span class="text-sm text-gray-600 ml-2">(El precio puede variar según opciones)</span>
                            @endif
                        </div>
                          <!-- Componente para agregar al carrito -->
                        @livewire('add-to-cart', ['product' => $product])
                    </div>
                </div>
            </div>
            
            <!-- Características y opciones -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4">Características y especificaciones</h2>
                
                @if($product->options->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($product->options->groupBy('name') as $optionName => $options)
                            <div class="border-b border-gray-200 pb-4 mb-4">
                                <h3 class="font-medium text-lg mb-2">{{ $optionName }}</h3>
                                <ul class="space-y-2">
                                    @foreach($options as $option)
                                        <li class="flex justify-between">
                                            <span class="text-gray-600">{{ $option->name }}</span>
                                            <span class="font-medium">{{ $option->pivot->value }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No hay características disponibles para este producto.</p>
                @endif
            </div>            
            <!-- Sección de reseñas y valoraciones -->
            <div class="mb-8">
                @livewire('product-reviews', ['product' => $product])
            </div>
            
            <!-- Productos relacionados -->
            @if($relatedProducts->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Productos relacionados</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                @if($relatedProduct->image_path)
                                    <img src="{{ $relatedProduct->image }}" 
                                        alt="{{ $relatedProduct->name }}" 
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500">Sin imagen</span>
                                    </div>
                                @endif
                                
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg mb-2 line-clamp-2">{{ $relatedProduct->name }}</h3>
                                    <p class="text-blue-600 font-bold">${{ number_format($relatedProduct->price, 2) }}</p>
                                    
                                    <div class="mt-4">
                                        <a href="{{ route('products.show', $relatedProduct) }}" 
                                           class="block w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors text-center">
                                            Ver detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-container>
    </div>
      @push('js')
    <script>
        // Script para manejar eventos de Livewire
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('product-added-to-cart', () => {
                console.log('Producto agregado al carrito');
                // Aquí podríamos actualizar el contador del carrito o mostrar una notificación
            });
        });
    </script>
    @endpush
@endsection
