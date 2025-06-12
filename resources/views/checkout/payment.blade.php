<x-app-layout>
    <div class="container py-8">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-2xl font-semibold mb-6">Opciones de Pago</h1>
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium mb-4">Dirección de Envío</h2>
                    
                    <div class="border rounded-lg p-4 mb-6">
                        <p class="font-semibold">{{ $address->description }}</p>
                        <p>{{ $address->distrito }}, {{ $address->departamento }}</p>
                        
                        @if($address->latitude && $address->longitude)
                            <div class="mt-2">
                                <div class="h-28 bg-gray-100 rounded" 
                                    x-data="{}" 
                                    x-init="
                                        setTimeout(() => {
                                            const map = L.map($el).setView([{{ $address->latitude }}, {{ $address->longitude }}], 15);
                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                                            L.marker([{{ $address->latitude }}, {{ $address->longitude }}]).addTo(map);
                                        }, 100)">
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <h2 class="text-lg font-medium mb-4">Método de Pago</h2>
                      <form action="{{ route('checkout.process-payment') }}" method="POST" id="payment-form">
                        @csrf
                        
                        <div class="space-y-4">
                            <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" x-data="{ selected: true }" @click="selected = true; document.getElementById('payment_option_1').checked = true; document.getElementById('card-element-container').classList.remove('hidden');">
                                <div class="flex items-center">
                                    <input type="radio" id="payment_option_1" name="payment_method" value="credit_card" class="mr-3" checked x-model="selected" @change="if(selected) { document.getElementById('card-element-container').classList.remove('hidden'); }">
                                    <label for="payment_option_1" class="cursor-pointer flex items-center">
                                        <i class="fas fa-credit-card text-blue-500 text-2xl mr-3"></i>
                                        <div>
                                            <p class="font-medium">Tarjeta de Crédito / Débito</p>
                                            <p class="text-sm text-gray-600">Pago seguro con tarjeta</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Contenedor para el elemento de tarjeta de Stripe -->
                            <div id="card-element-container" class="p-4 border rounded-lg bg-gray-50">
                                <div class="mb-4">
                                    <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">Información de la tarjeta</label>
                                    <div id="card-element" class="p-3 border border-gray-300 rounded-md bg-white"></div>
                                    <div id="card-errors" role="alert" class="text-red-500 text-sm mt-1"></div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="card-name" class="block text-sm font-medium text-gray-700 mb-1">Nombre en la tarjeta</label>
                                        <input type="text" id="card-name" class="w-full border-gray-300 rounded-md" placeholder="Nombre completo">
                                    </div>
                                    <div>
                                        <label for="card-email" class="block text-sm font-medium text-gray-700 mb-1">Email para recibo</label>
                                        <input type="email" id="card-email" class="w-full border-gray-300 rounded-md" placeholder="email@ejemplo.com">
                                    </div>
                                </div>
                                <input type="hidden" name="payment_intent_id" id="payment_intent_id">
                                <input type="hidden" name="payment_method_id" id="payment_method_id">
                            </div>
                            
                            <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer" x-data="{ selected: false }" @click="selected = true; document.getElementById('payment_option_2').checked = true; document.getElementById('card-element-container').classList.add('hidden');">
                                <div class="flex items-center">
                                    <input type="radio" id="payment_option_2" name="payment_method" value="cash" class="mr-3" x-model="selected" @change="if(selected) { document.getElementById('card-element-container').classList.add('hidden'); }">
                                    <label for="payment_option_2" class="cursor-pointer flex items-center">
                                        <i class="fas fa-money-bill-wave text-green-500 text-2xl mr-3"></i>
                                        <div>
                                            <p class="font-medium">Pago Contra Entrega</p>
                                            <p class="text-sm text-gray-600">Paga cuando recibas tu pedido</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t mt-6 pt-6">
                            <div class="flex justify-between mb-2">
                                <span>Subtotal</span>
                                <span>S/ {{ number_format(session('cart')->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                @if (session('cart')->total >= 100)
                                    <span>Envío</span>
                                    <span class="text-green-600">Gratis</span>
                                @else
                                    <span>Envío</span>
                                    <span>S/ 4.99</span>
                                    
                                @endif
                            </div>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span>$ {{ number_format(session('cart')->total, 2) }}</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('shipping.index') }}" class="px-4 py-2 border border-gray-300 rounded-md mr-3 text-gray-700">
                                Volver
                            </a>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Confirmar Compra
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
      @push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    @endpush

    @push('js')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Stripe con tu clave pública            const stripe = Stripe('{{ config('services.stripe.key') }}');
            const elements = stripe.elements();
            const clientSecret = '{{ $clientSecret }}';
            
            // Crear el elemento de tarjeta
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        color: '#32325d',
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                }
            });
            
            // Montar el elemento en el DOM
            cardElement.mount('#card-element');
            
            // Manejar errores en tiempo real
            cardElement.addEventListener('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            
            // Manejar el envío del formulario
            const form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                // Solo procesar si se eligió tarjeta de crédito
                if (document.getElementById('payment_option_1').checked) {
                    event.preventDefault();
                    
                    // Mostrar un indicador de carga
                    const submitButton = form.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
                    
                    // Crear un método de pago para la tarjeta
                    stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                        billing_details: {
                            name: document.getElementById('card-name').value,
                            email: document.getElementById('card-email').value,
                        },
                    }).then(function(result) {
                        if (result.error) {
                            // Mostrar error
                            const errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                            submitButton.disabled = false;
                            submitButton.innerHTML = 'Confirmar Compra';
                        } else {
                            // Enviar el ID del método de pago al servidor
                            document.getElementById('payment_method_id').value = result.paymentMethod.id;
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
