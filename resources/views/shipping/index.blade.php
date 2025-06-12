<x-app-layout>
    <x-container class="mt-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="col-span-1 md:col-span-2">
                @livewire('shipping-address')
            </div>
            
            <div class="col-span-1">
                <div class="bg-white rounded-lg shadow overflow-hidden sticky top-4">
                    <header class="bg-gray-900 px-4 py-2">
                        <h2 class="text-white text-lg">
                            Resumen de Compra
                        </h2>
                    </header>
                    
                    <div class="p-4">
                        @if(session()->has('cart') && session('cart')->items->isNotEmpty())
                            <div class="space-y-4">
                                @foreach(session('cart')->items as $item)
                                    <div class="flex items-center pb-4 border-b">
                                        <img src="{{ Storage::url($item->options['image']) }}" alt="{{ $item->name }}" class="h-16 w-16 object-cover rounded mr-4">
                                        <div class="flex-1">
                                            <p class="font-medium truncate">{{ $item->name }}</p>
                                            <p class="text-sm text-gray-600">Cantidad: {{ $item->qty }}</p>
                                            <p class="text-sm font-semibold">S/ {{ number_format($item->price * $item->qty, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="border-t mt-4 pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span>$ {{ number_format(session('cart')->total / 1.18, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">IGV (18%)</span>
                                    <span>$ {{ number_format(session('cart')->total - (session('cart')->total / 1.18), 2) }}</span>
                                </div>
                                <div class="flex justify-between font-bold text-lg pt-2 border-t">
                                    <span>Total</span>
                                    <span>$ {{ number_format(session('cart')->total, 2) }}</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('cart.index') }}" class="block text-center text-blue-600 hover:underline">
                                    <i class="fas fa-shopping-cart mr-1"></i> Editar carrito
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-500">Tu carrito está vacío</p>
                                <a href="{{ route('welcome') }}" class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Ir a comprar
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
