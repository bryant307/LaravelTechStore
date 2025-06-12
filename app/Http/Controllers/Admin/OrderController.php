<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Order::with('user', 'address');
        
        // Filtrar por status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Búsqueda por número de orden
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.orders.index', compact('orders'));
    }

    

    

    /**
     * Muestra los detalles de un pedido específico.
     */
    public function show(string $id)
    {
        $order = Order::with('items.product', 'items.productVariant', 'user', 'address')
            ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Muestra el formulario para editar un pedido específico.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Actualiza el estado de un pedido específico.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
            'tracking_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $order = Order::findOrFail($id);
        
        $order->status = $request->status;
        $order->tracking_number = $request->tracking_number;
        $order->notes = $request->notes;
        $order->save();
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pedido actualizado correctamente');
    }

    /**
     * Elimina un pedido específico.
     */
    public function destroy(string $id)
    {
        //
    }
}
