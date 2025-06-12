<div>
    <form wire:submit.prevent="addToCart" class="mb-6">
        <!-- Variantes del producto -->
        @if($hasVariants)
            <div class="mb-4">
                <h3 class="block mb-2 font-medium text-gray-700">Opciones disponibles:</h3>
                
                @foreach($product->productVariants->groupBy('option_id') as $optionId => $variants)
                    @php 
                        $option = App\Models\Option::find($optionId);
                        $optionName = $option ? $option->name : 'Opción';
                    @endphp
                    
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $optionName }}:</label>
                        <div class="flex flex-wrap gap-2">                            @foreach($variants as $variant)
                                <label class="inline-flex items-center">
                                    <input type="radio"
                                           name="selectedVariants[{{ $optionId }}]"
                                           wire:model="selectedVariants.{{ $optionId }}"
                                           value="{{ $variant->id }}"
                                           class="mr-2 text-blue-600 border-gray-300 focus:ring-blue-500">
                                           
                                    @if(strtolower($optionName) == 'color' || mb_strpos(strtolower($optionName), 'color') !== false)
                                        <span class="inline-flex items-center">
                                            <span class="w-6 h-6 rounded-full border mr-2" 
                                                  style="background-color: {{ $variant->value }}"></span>
                                            <span>{{ $variant->value }}</span>
                                        </span>
                                    @else
                                        <span>{{ $variant->value }}</span>
                                    @endif
                                    
                                    @if($variant->price && $variant->price != $product->price)
                                        <span class="ml-1 text-sm text-blue-600">
                                            (${{ number_format($variant->price, 2) }})
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        <!-- Cantidad -->
        <div class="mb-4">
            <label for="quantity" class="block mb-2 text-sm font-medium text-gray-700">Cantidad:</label>
            <div class="flex items-center">
                <button type="button" 
                        class="bg-gray-200 text-gray-700 py-2 px-4 rounded-l hover:bg-gray-300"
                        wire:click="decrement">
                    <i class="fas fa-minus"></i>
                </button>
                <input type="number" 
                       id="quantity" 
                       wire:model="quantity" 
                       min="1" 
                       class="w-16 py-2 px-3 text-center border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <button type="button" 
                        class="bg-gray-200 text-gray-700 py-2 px-4 rounded-r hover:bg-gray-300"
                        wire:click="increment">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        
        <!-- Precio con variante (dinámico) -->
        <div class="mb-4">
            <p class="text-xl font-bold text-blue-600">
                Precio: ${{ number_format($price, 2) }}
            </p>
        </div>
        
        <!-- Botón Agregar al carrito -->
        <button type="submit" 
                class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
            <i class="fas fa-shopping-cart mr-2"></i> Agregar al carrito
        </button>
        
        <!-- Mensaje de confirmación -->
        @if (session()->has('message'))
            <div class="mt-4 p-3 bg-green-100 border border-green-300 rounded-lg">
                <p class="text-green-700 text-sm">{{ session('message') }}</p>
            </div>
        @endif
    </form>
</div>
