<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShippingAddress extends Component
{
    public $addresses = [];
    public $showForm = false;
    public $showMap = false;

    // Form fields
    public $type = 1;
    public $description;
    public $distrito;
    public $departamento;
    public $receiver = 1;
    public $receiver_info = [];
    public $latitude = null;
    public $longitude = null;
    public $default = false;
    public $address_id = null;

    protected $rules = [
        'type' => 'required|integer',
        'description' => 'required|string|max:255',
        'distrito' => 'required|string|max:255',
        'departamento' => 'required|string|max:255',
        'receiver' => 'required|integer',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'default' => 'boolean',
    ];

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        if (Auth::check()) {
            $this->addresses = Auth::user()->addresses;
        }
    }

    public function showAddressForm()
    {
        $this->reset([
            'type', 'description', 'distrito', 'departamento', 'receiver',
            'latitude', 'longitude', 'default', 'address_id'
        ]);
        $this->receiver_info = [];
        $this->showForm = true;
    }

    public function toggleMap()
    {
        $this->showMap = !$this->showMap;
    }

    public function setMapCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->showMap = false;
    }

    public function editAddress($id)
    {
        $address = Address::find($id);
        if ($address && $address->user_id === Auth::id()) {
            $this->address_id = $address->id;
            $this->type = $address->type;
            $this->description = $address->description;
            $this->distrito = $address->distrito;
            $this->departamento = $address->departamento;
            $this->receiver = $address->receiver;
            $this->receiver_info = $address->receiver_info ?? [];
            $this->latitude = $address->latitude;
            $this->longitude = $address->longitude;
            $this->default = $address->default;
            $this->showForm = true;
        }
    }

    public function saveAddress()
    {
        $this->validate();

        if ($this->address_id) {
            // Update existing
            $address = Address::find($this->address_id);
            if ($address && $address->user_id === Auth::id()) {
                $address->update([
                    'type' => $this->type,
                    'description' => $this->description,
                    'distrito' => $this->distrito,
                    'departamento' => $this->departamento,
                    'receiver' => $this->receiver,
                    'receiver_info' => $this->receiver_info,
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                    'default' => $this->default,
                ]);
            }
        } else {
            // Create new
            Auth::user()->addresses()->create([
                'type' => $this->type,
                'description' => $this->description,
                'distrito' => $this->distrito,
                'departamento' => $this->departamento,
                'receiver' => $this->receiver,
                'receiver_info' => $this->receiver_info,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'default' => $this->default,
            ]);
        }

        // If this is the default address, update others
        if ($this->default) {
            Auth::user()->addresses()
                ->where('id', '!=', $this->address_id ?? 0)
                ->update(['default' => false]);
        }

        $this->showForm = false;
        $this->loadAddresses();
        $this->dispatch('address-updated');
    }

    public function deleteAddress($id)
    {
        $address = Address::find($id);
        if ($address && $address->user_id === Auth::id()) {
            $address->delete();
            $this->loadAddresses();
        }
    }

    public function proceedToCheckout()
    {
        // Verificar si hay una dirección predeterminada
        $defaultAddress = Auth::user()->addresses()->where('default', true)->first();
        
        if (!$defaultAddress) {
            // Si no hay dirección predeterminada, verificamos si hay alguna dirección
            if (count($this->addresses) > 0) {
                // Establece la primera dirección como predeterminada
                $firstAddress = $this->addresses->first();
                $firstAddress->update(['default' => true]);
                $defaultAddress = $firstAddress;
            } else {
                // No hay direcciones disponibles
                session()->flash('error', 'Debes agregar al menos una dirección de envío antes de continuar.');
                $this->showAddressForm();
                return;
            }
        }
        
        // Verificar que haya productos en el carrito
        $cart = session('cart');
        if (!$cart || count($cart->items) === 0) {
            session()->flash('error', 'Tu carrito está vacío. Agrega productos antes de proceder al pago.');
            return redirect()->route('cart.index');
        }
        
        // Guardar la información de la dirección en la sesión para usarla en el checkout
        session()->put('shipping_address_id', $defaultAddress->id);
        
        // Mostrar mensaje de éxito
        session()->flash('success', 'Dirección seleccionada correctamente.');
        
        // Redireccionar a la página de checkout
        return redirect()->route('checkout.payment');
    }

    public function render()
    {
        return view('livewire.shipping-address');
    }
}
