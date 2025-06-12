<div>
    <section class="bg-white rounded-lg shadow overflow-hidden">
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-t">
                {{ session('error') }}
            </div>
        @endif
        
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-t">
                {{ session('success') }}
            </div>
        @endif
        
        <header class="bg-gray-900 px-4 py-2 flex justify-between items-center">
            <h2 class="text-white text-lg">
                Direcciones de envío guardadas
            </h2>
            <button wire:click="showAddressForm" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none">
                <i class="fas fa-plus mr-2"></i> Nueva dirección
            </button>
        </header>
        
        <div class="p-4">
            @if(count($addresses) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($addresses as $address)
                        <div class="border rounded-lg p-4 relative {{ $address->default ? 'border-green-500' : 'border-gray-300' }}">
                            @if($address->default)
                                <div class="absolute top-0 right-0 bg-green-500 text-white px-2 py-1 text-xs rounded-bl">
                                    Predeterminada
                                </div>
                            @endif
                            
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
                            
                            <div class="mt-3 flex space-x-2">
                                <button wire:click="editAddress({{ $address->id }})" class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                    Editar
                                </button>
                                <button wire:click="deleteAddress({{ $address->id }})" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600" 
                                        onclick="return confirm('¿Estás seguro de eliminar esta dirección?')">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Botón Proceder al Pago -->
                <div class="mt-6 flex justify-end">
                    <button wire:click="proceedToCheckout" class="px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i> Proceder al pago
                    </button>
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-gray-500">No tienes direcciones guardadas</p>
                    <button wire:click="showAddressForm" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Agregar dirección
                    </button>
                </div>
            @endif
        </div>
    </section>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.5);">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">{{ $address_id ? 'Editar' : 'Nueva' }} dirección</h3>
                    <button wire:click="$set('showForm', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveAddress">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de dirección</label>
                            <select wire:model="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="1">Casa</option>
                                <option value="2">Trabajo</option>
                                <option value="3">Otro</option>
                            </select>
                            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <input type="text" wire:model="description" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Ej: Urb. Ciudad de las al">
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Distrito</label>
                            <input type="text" wire:model="distrito" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @error('distrito') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                            <input type="text" wire:model="departamento" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @error('departamento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Ubicación en el mapa</label>
                            <button type="button" wire:click="toggleMap" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ $showMap ? 'Ocultar mapa' : 'Seleccionar punto en el mapa' }}
                            </button>
                        </div>
                        
                        @if($latitude && $longitude)
                            <div class="mb-2 text-sm text-gray-600">
                                Coordenadas seleccionadas: {{ number_format($latitude, 6) }}, {{ number_format($longitude, 6) }}
                            </div>
                        @endif

                        @if($showMap)
                            <div 
                                x-data="{
                                    initMap() {
                                        const defaultLat = {{ $latitude ?? -12.046374 }};
                                        const defaultLng = {{ $longitude ?? -77.042793 }};
                                        
                                        const map = L.map($el).setView([defaultLat, defaultLng], 13);
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                                        
                                        let marker;
                                        if ({{ $latitude && $longitude ? 'true' : 'false' }}) {
                                            marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);
                                        }
                                        
                                        map.on('click', function(e) {
                                            if (marker) {
                                                marker.setLatLng(e.latlng);
                                            } else {
                                                marker = L.marker(e.latlng, {draggable: true}).addTo(map);
                                            }
                                            @this.setMapCoordinates(e.latlng.lat, e.latlng.lng);
                                        });
                                        
                                        if (marker) {
                                            marker.on('dragend', function(e) {
                                                const position = marker.getLatLng();
                                                @this.setMapCoordinates(position.lat, position.lng);
                                            });
                                        }
                                    }
                                }" 
                                x-init="setTimeout(() => initMap(), 100)" 
                                class="h-96 bg-gray-100 rounded-lg mb-4">
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="default" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Establecer como dirección predeterminada</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    @endif
</div>

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
@endpush

@push('js')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
@endpush
