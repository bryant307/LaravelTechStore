<x-app-layout>
    <div class="container py-8">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="flex justify-center">
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check text-green-500 text-4xl"></i>
                    </div>
                </div>
                
                <h1 class="text-2xl font-bold mt-6 mb-4">¡Gracias por tu compra!</h1>
                
                <p class="text-gray-600 mb-6">
                    Tu pedido ha sido recibido y está siendo procesado. Recibirás un correo electrónico con los detalles de tu compra.
                </p>
                  <div class="border-t border-b py-6 my-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                        <div>
                            <h3 class="font-medium text-gray-700 mb-2">Número de Pedido:</h3>
                            <p class="text-lg font-semibold">#{{ rand(10000, 99999) }}</p>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-700 mb-2">Fecha:</h3>
                            <p class="text-lg">{{ now()->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-700 mb-2">Total:</h3>
                            <p class="text-lg">S/ {{ number_format(session('cart')->total ?? 0, 2) }}</p>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-700 mb-2">Método de Pago:</h3>
                            <p class="text-lg">
                                @if(session('payment_method') === 'credit_card')
                                    Tarjeta de Crédito/Débito
                                    @if(session('payment_intent_id'))
                                        <span class="block text-sm text-gray-500 mt-1">ID de transacción: {{ session('payment_intent_id') }}</span>
                                    @endif
                                @else
                                    Pago Contra Entrega
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row justify-center space-y-3 md:space-y-0 md:space-x-4">
                    <a href="{{ route('welcome') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        Continuar comprando
                    </a>
                    <a href="#" class="bg-gray-100 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
                        Ver mis pedidos
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
