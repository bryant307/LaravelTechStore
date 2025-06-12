<div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Mi Carrito</h1>
            @if(count($cart->items) > 0)
                <button wire:click="clearCart" 
                        class="text-sm text-red-600 hover:text-red-800 flex items-center">
                    <i class="fas fa-trash mr-1"></i> Vaciar carrito
                </button>
            @endif
        </div>
        
        @if(count($cart->items) > 0)
            <div class="mb-8">
                <div class="hidden md:grid grid-cols-6 gap-4 py-3 border-b border-gray-200 font-medium text-gray-600">
                    <div class="col-span-3">Producto</div>
                    <div class="text-center">Precio</div>
                    <div class="text-center">Cantidad</div>
                    <div class="text-right">Subtotal</div>
                </div>
                  @foreach($cart->items as $item)
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 py-4 border-b border-gray-200">
                        <!-- Producto (imagen + información) -->
                        <div class="col-span-1 md:col-span-3">
                            <div class="flex space-x-4">
                                <div class="w-24 h-24 flex-shrink-0">                                @if(isset($item['options']['image']))
                                        <img src="{{ $item['options']['image'] }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="w-full h-full object-cover rounded">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded">
                                            <span class="text-gray-500 text-xs">Sin imagen</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-medium">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">ID: {{ $item['id'] }}</p>
                                    <button wire:click="removeItem({{ $item['id'] }})" 
                                            class="text-xs text-red-600 hover:text-red-800 mt-2 flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                          <!-- Precio unitario -->
                        <div class="md:text-center">
                            <div class="md:hidden text-sm font-medium text-gray-600 mb-1">Precio:</div>
                            <span>${{ number_format($item['price'], 2) }}</span>
                        </div>
                        
                        <!-- Cantidad -->
                        <div class="md:text-center">
                            <div class="md:hidden text-sm font-medium text-gray-600 mb-1">Cantidad:</div>                            <div class="flex items-center md:justify-center">
                                <button wire:click="decrementQuantity({{ $item['id'] }})" 
                                        class="bg-gray-200 text-gray-700 py-1 px-2 rounded-l hover:bg-gray-300">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       wire:model.blur="quantities.{{ $item['id'] }}" 
                                       wire:change="updateQuantity({{ $item['id'] }})"
                                       min="1" 
                                       class="w-12 py-1 px-1 text-center border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <button wire:click="incrementQuantity({{ $item['id'] }})"
                                        class="bg-gray-200 text-gray-700 py-1 px-2 rounded-r hover:bg-gray-300">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                          <!-- Subtotal -->
                        <div class="md:text-right">
                            <div class="md:hidden text-sm font-medium text-gray-600 mb-1">Subtotal:</div>
                            <span class="font-medium">${{ number_format($item['qty'] * $item['price'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Resumen -->
            <div class="mt-8 flex flex-col md:flex-row md:items-start md:justify-between">
                <div class="mb-6 md:mb-0 md:w-1/2">
                    <h3 class="font-medium mb-2">Políticas de envío</h3>
                    <p class="text-sm text-gray-600">
                        Envío gratuito en pedidos superiores a $100. Para pedidos menores, el costo de envío es de $4.99.
                    </p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg md:w-1/3">
                    <h3 class="font-medium mb-4 border-b pb-2">Resumen de la compra</h3>
                    
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($cart->total, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between mb-2">
                        <span>Envío:</span>
                        <span>
                            @if($cart->total >= 100)
                                <span class="text-green-600">Gratis</span>
                            @else
                                $4.99
                            @endif
                        </span>
                    </div>
                    
                    <div class="border-t pt-2 mt-2 font-bold flex justify-between">
                        <span>Total:</span>
                        <span>
                            ${{ number_format($cart->total >= 100 ? $cart->total : $cart->total + 4.99  , 2) }}
                        </span>
                    </div>
                    
                    <a href="{{route('shipping.index')}}" class="mt-4 block w-full bg-blue-600 text-white text-center py-2 px-4 rounded hover:bg-blue-700">
                        Continuar con la compra
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-shopping-cart text-6xl"></i>
                </div>
                <h2 class="text-2xl font-medium mb-2">Tu carrito está vacío</h2>
                <p class="text-gray-600 mb-6">Parece que aún no has añadido productos a tu carrito.</p>
                <a href="{{ route('welcome') }}" class="bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700">
                    Ir de compras
                </a>
            </div>
        @endif
    </div>
</div>
