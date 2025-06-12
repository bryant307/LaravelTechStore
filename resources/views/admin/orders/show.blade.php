<x-admin-layout>    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle de Pedido') }} #{{ $order->order_number }}
            </h2>
            <div>
                <a href="{{ route('admin.orders.edit', $order) }}" class="px-4 py-2 mr-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Cambiar estado
                </a>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Volver a la lista
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Información del pedido</h3>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($order->status == 'completed') bg-green-100 text-green-800
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @elseif($order->status == 'refunded') bg-purple-100 text-purple-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <!-- Detalles del pedido -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">Información del cliente</h4>
                            <div class="flex items-center mb-2">
                                <img src="{{ $order->user->profile_photo_url }}" alt="{{ $order->user->name }}" class="h-10 w-10 rounded-full mr-3">
                                <div>
                                    <p class="text-base font-medium">{{ $order->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                                </div>
                            </div>
                            <div class="mt-2 text-sm">
                                <p class="text-gray-600"><strong>ID del cliente:</strong> {{ $order->user->id }}</p>
                                <p class="text-gray-600"><strong>Registrado:</strong> {{ $order->user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Fecha del pedido</h4>
                            <p class="text-base">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Método de pago</h4>
                            <p class="text-base">{{ $order->payment_method == 'credit_card' ? 'Tarjeta de crédito' : 'Pago contra entrega' }}</p>
                            @if($order->payment_id)
                                <p class="text-sm text-gray-500">ID de pago: {{ $order->payment_id }}</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Total</h4>
                            <p class="text-base font-semibold">S/ {{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Dirección de envío</h4>
                            <p class="text-base">{{ $order->address->description }}</p>
                            <p class="text-sm text-gray-500">{{ $order->address->distrito }}, {{ $order->address->departamento }}</p>
                            @if($order->address->latitude && $order->address->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $order->address->latitude }},{{ $order->address->longitude }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm">Ver en mapa</a>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Número de seguimiento</h4>
                            <p class="text-base">{{ $order->tracking_number ?: 'No asignado' }}</p>
                        </div>
                    </div>
                    
                    <!-- Notas -->
                    @if($order->notes)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Notas</h4>
                            <div class="bg-gray-50 p-4 rounded">
                                {{ $order->notes }}
                            </div>
                        </div>
                    @endif
                    
                    <!-- Acciones -->
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Editar pedido
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Productos -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Productos</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->name }}
                                                    </div>
                                                    @if($item->productVariant)
                                                        <div class="text-sm text-gray-500">
                                                            {{ $item->productVariant->value }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            S/ {{ number_format($item->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            S/ {{ number_format($item->subtotal, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Fila del total -->
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium text-gray-900">
                                        Total:
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        S/ {{ number_format($order->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
