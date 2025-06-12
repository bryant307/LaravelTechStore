{{-- plantilla del dashboard --}}
{{-- Se agrega el breadcrumb para el diseño de la vista --}}
{{-- Diseño del breadcrumb --}}

<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ]
]">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                    alt="{{ Auth::user()->name }}" />

                <div class="ml-4 flex-1">
                    <h2 class="text-lg font-semibold">
                        Bienvenido, {{ Auth::user()->name }}
                    </h2>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm hover:text-blue-500">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">
                Laravel TechStore - Panel de Control
            </h2>
            <p class="text-gray-600">Fecha actual: {{ now()->format('d/m/Y') }}</p>
            <p class="text-gray-600">Hora: {{ now()->format('H:i') }}</p>
        </div>
    </div>

    <!-- KPI Cards - Indicadores clave -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <i class="fas fa-shopping-cart text-blue-500"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pedidos Hoy</p>
                    <p class="text-2xl font-semibold">{{ App\Models\Order::whereDate('created_at', today())->count() }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.orders.index') }}" class="text-blue-500 text-sm hover:underline">Ver pedidos</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <i class="fas fa-dollar-sign text-green-500"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ingresos Hoy</p>
                    <p class="text-2xl font-semibold">$ {{ number_format(App\Models\Order::whereDate('created_at', today())->sum('total_amount'), 2) }}</p>
                </div>
            </div>
            <div class="mt-4">
                @php
                    $todayIncome = App\Models\Order::whereDate('created_at', today())->sum('total_amount');
                    $yesterdayIncome = App\Models\Order::whereDate('created_at', today()->subDay())->sum('total_amount');
                    $percentChange = $yesterdayIncome > 0 ? (($todayIncome / $yesterdayIncome) - 1) * 100 : 0;
                @endphp
                <span class="{{ $percentChange >= 0 ? 'text-green-500' : 'text-red-500' }} text-sm">
                    <i class="fas {{ $percentChange >= 0 ? 'fa-chart-line' : 'fa-chart-line-down' }}"></i>
                    {{ $percentChange != 0 ? ($percentChange > 0 ? '+' : '') . number_format($percentChange, 1) . '%' : '0%' }}
                </span>
                <span class="text-gray-500 text-sm ml-1">vs ayer</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <i class="fas fa-users text-yellow-500"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nuevos Usuarios</p>
                    <p class="text-2xl font-semibold">{{ App\Models\User::whereDate('created_at', today())->count() }}</p>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-gray-500 text-sm">Total: {{ App\Models\User::count() }}</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <i class="fas fa-box text-red-500"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Productos Activos</p>
                    <p class="text-2xl font-semibold">{{ App\Models\Product::where('available', true)->count() }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.products.index') }}" class="text-blue-500 text-sm hover:underline">Gestionar productos</a>
            </div>
        </div>
    </div>

    <!-- Estado de Pedidos y Gráfico -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold mb-4">Estado de Pedidos</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse(App\Models\Order::latest()->take(5)->get() as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:underline">{{ $order->order_number }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">$ {{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->status == 'pending')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                @elseif($order->status == 'processing')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">En proceso</span>
                                @elseif($order->status == 'completed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completado</span>
                                @elseif($order->status == 'cancelled')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelado</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay pedidos recientes</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('admin.orders.index') }}" class="text-blue-500 hover:underline">Ver todos los pedidos</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Estado de Pedidos</h2>
            <div class="relative">
                <!-- Este es un gráfico simulado en HTML/CSS, podría ser reemplazado por Chart.js -->
                <div class="flex items-end h-64 mt-4 space-x-2">
                    @php
                        $statusCounts = [
                            'pending' => App\Models\Order::where('status', 'pending')->count(),
                            'processing' => App\Models\Order::where('status', 'processing')->count(),
                            'completed' => App\Models\Order::where('status', 'completed')->count(),
                            'cancelled' => App\Models\Order::where('status', 'cancelled')->count(),
                        ];
                        $max = max($statusCounts);
                    @endphp
                    
                    <div class="w-1/4 flex flex-col items-center">
                        <div class="w-full bg-yellow-400 rounded-t" style="height: {{ $max ? ($statusCounts['pending'] / $max) * 200 : 0 }}px"></div>
                        <div class="w-full text-center text-xs mt-1">Pendientes</div>
                        <div class="font-bold">{{ $statusCounts['pending'] }}</div>
                    </div>
                    
                    <div class="w-1/4 flex flex-col items-center">
                        <div class="w-full bg-blue-400 rounded-t" style="height: {{ $max ? ($statusCounts['processing'] / $max) * 200 : 0 }}px"></div>
                        <div class="w-full text-center text-xs mt-1">Proceso</div>
                        <div class="font-bold">{{ $statusCounts['processing'] }}</div>
                    </div>
                    
                    <div class="w-1/4 flex flex-col items-center">
                        <div class="w-full bg-green-400 rounded-t" style="height: {{ $max ? ($statusCounts['completed'] / $max) * 200 : 0 }}px"></div>
                        <div class="w-full text-center text-xs mt-1">Completados</div>
                        <div class="font-bold">{{ $statusCounts['completed'] }}</div>
                    </div>
                    
                    <div class="w-1/4 flex flex-col items-center">
                        <div class="w-full bg-red-400 rounded-t" style="height: {{ $max ? ($statusCounts['cancelled'] / $max) * 200 : 0 }}px"></div>
                        <div class="w-full text-center text-xs mt-1">Cancelados</div>
                        <div class="font-bold">{{ $statusCounts['cancelled'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventario y Productos Populares -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">            <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Productos con Bajo Inventario</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(App\Models\Product::where('track_inventory', true)->where('stock', '<', 10)->where('available', true)->orderBy('stock')->take(5)->get() as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:underline">{{ $product->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->stock <= 0)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Sin stock</span>
                                @elseif($product->stock <= 5)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Crítico</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Bajo</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('admin.products.index') }}" class="text-blue-500 hover:underline">Administrar inventario</a>
            </div>
        </div>            <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Productos Más Vendidos</h2>
            <div class="overflow-x-auto">
                @php
                    $topProducts = DB::table('order_items')
                        ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
                        ->groupBy('product_id')
                        ->orderByDesc('total_qty')
                        ->take(5)
                        ->get();
                @endphp
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad Vendida</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topProducts as $item)
                        @php $product = App\Models\Product::find($item->product_id); @endphp
                        @if($product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:underline">{{ $product->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->total_qty }}</td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">No hay datos de ventas disponibles</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Actividad Reciente</h2>
        <div class="space-y-4">
            @forelse(App\Models\Order::latest()->take(5)->get() as $order)
            <div class="border-l-4 border-blue-500 pl-4 py-2">
                <div class="flex justify-between">
                    <div>
                        <p class="font-medium">Nuevo pedido: {{ $order->order_number }}</p>
                        <p class="text-sm text-gray-600">Cliente: {{ $order->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">
                            Método de pago: 
                            @if($order->payment_method == 'credit_card')
                                Tarjeta
                            @elseif($order->payment_method == 'cash')
                                Contra Entrega
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="font-medium">$ {{ number_format($order->total_amount, 2) }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-gray-500">No hay actividad reciente para mostrar</div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
