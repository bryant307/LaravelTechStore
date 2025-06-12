<x-app-layout>
    <x-container class="py-8">
        <div class="flex items-center mb-6">
            <a href="{{ route('welcome') }}" class="text-blue-600 hover:text-blue-800 mr-2">
                <i class="fas fa-home"></i> Inicio
            </a>
            <span class="text-gray-500 mx-2">/</span>
            <span class="text-gray-700">Búsqueda</span>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">
            Resultados de búsqueda: "<span class="text-blue-600">{{ $query }}</span>"
        </h1>

        @if(empty($query))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Por favor ingresa un término de búsqueda para encontrar productos.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="text-center py-8">
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al inicio
                </a>
            </div>
        @else
            @livewire('filter', ['search' => $query, 'family_id' => null])
        @endif
    </x-container>
</x-app-layout>
