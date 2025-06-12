<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTracking extends Component
{
    use WithPagination;
    
    public $selectedOrder = null;
    public $orderDetails = null;
    
    public function mount()
    {
        // Si hay un ID de pedido en la URL, seleccionar ese pedido
        if (request()->has('order_id')) {
            $this->selectOrder(request()->order_id);
        }
    }
    
    public function selectOrder($orderId)
    {
        $order = Order::with('items.product', 'address')
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
            
        $this->selectedOrder = $order;
        $this->orderDetails = $order;
    }
    
    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.order-tracking', [
            'orders' => $orders
        ]);
    }
}
