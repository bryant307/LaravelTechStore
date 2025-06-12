<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\CartItem;

class Cart
{
    public $items;
    public $total = 0;
    public $items_count = 0;

    public function __construct()
    {
        $this->items = collect();
    }

    /**
     * Prepara el objeto para la serialización.
     *
     * @return array
     */
    public function __serialize(): array
    {
        // Convertir cada CartItem a un array simple
        $itemsArray = [];
        foreach ($this->items as $item) {
            if ($item instanceof \App\Models\CartItem) {
                $itemsArray[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'qty' => $item->qty,
                    'options' => $item->options
                ];
            }
        }
        
        return [
            'items' => $itemsArray,
            'total' => $this->total,
            'items_count' => $this->items_count
        ];
    }    /**
     * Restaura las propiedades del objeto después de la deserialización.
     *
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        try {
            // Inicializar con colección vacía
            $this->items = collect();
            
            // Verificar que 'items' existe y es un array
            if (isset($data['items']) && is_array($data['items'])) {
                Log::debug('Cart __unserialize: items count: ' . count($data['items']));
                
                foreach ($data['items'] as $itemData) {
                    try {
                        // Asegurarse de que itemData sea array antes de acceder a sus elementos
                        if (is_array($itemData)) {
                            $this->items->push(new \App\Models\CartItem(
                                $itemData['id'] ?? null,
                                $itemData['name'] ?? '',
                                $itemData['price'] ?? 0,
                                $itemData['qty'] ?? 1,
                                $itemData['options'] ?? []
                            ));
                        } else {
                            Log::debug('Cart __unserialize: itemData no es un array');
                        }
                    } catch (\Exception $e) {
                        Log::error('Error al reconstruir CartItem: ' . $e->getMessage());
                    }
                }
            } else {
                Log::debug('Cart __unserialize: no hay items o no es un array');
            }
            
            $this->total = $data['total'] ?? 0;
            $this->items_count = $data['items_count'] ?? 0;
            
        } catch (\Exception $e) {
            Log::error('Error en __unserialize: ' . $e->getMessage());
            $this->items = collect();
            $this->total = 0;
            $this->items_count = 0;
        }
    }    public static function getCurrent()
    {
        try {
            Log::debug('Cart getCurrent: iniciando');
            
            if (!Session::has('cart')) {
                Log::debug('Cart getCurrent: no hay carrito en sesión, creando nuevo');
                Session::put('cart', new self());
                return Session::get('cart');
            }
    
            // Intentar obtener el carrito
            $cart = Session::get('cart');
            
            // Verificar si el carrito es una instancia de la clase Cart
            if (!($cart instanceof self)) {
                Log::debug('Cart getCurrent: el carrito no es instancia de Cart, creando nuevo');
                // Si no es una instancia válida, crear un nuevo carrito
                Session::put('cart', new self());
                return Session::get('cart');
            }
    
            Log::debug('Cart getCurrent: carrito recuperado con éxito');
            
            // Asegurarnos de que $items sea una colección
            if (!($cart->items instanceof Collection)) {
                Log::debug('Cart getCurrent: items no es Collection, convirtiendo');
                $cart->items = collect($cart->items ?: []);
            }
    
            // Asegurarnos de que todos los elementos de la colección son instancias de CartItem
            $validItems = collect();
            $itemCount = 0;
            
            foreach ($cart->items as $item) {
                $itemCount++;
                if (!($item instanceof \App\Models\CartItem)) {
                    Log::debug('Cart getCurrent: item no es CartItem, intentando reparar');
                    // Si no es una instancia de CartItem, intentamos crear una nueva
                    if (is_object($item) || is_array($item)) {
                        $data = (array) $item;
                        if (isset($data['id'])) { // Solo si tiene un ID válido
                            $validItem = new \App\Models\CartItem(
                                $data['id'] ?? null,
                                $data['name'] ?? '',
                                $data['price'] ?? 0,
                                $data['qty'] ?? 1,
                                $data['options'] ?? []
                            );
                            $validItems->push($validItem);
                        }
                    }
                } else {
                    Log::debug('Cart getCurrent: item es CartItem válido');
                    $validItems->push($item);
                }
            }
            
            $cart->items = $validItems;
            Log::debug("Cart getCurrent: procesados {$itemCount} items, válidos: {$validItems->count()}");
            
            return $cart;
            
        } catch (\Exception $e) {
            // En caso de error, crear un nuevo carrito
            Log::error('Error en Cart::getCurrent: ' . $e->getMessage());
            Session::forget('cart');
            Session::put('cart', new self());
            return Session::get('cart');
        }
    }    public function add($id, $name, $price, $quantity = 1, $options = [])
    {
        try {
            if (!($this->items instanceof Collection)) {
                $this->items = collect();
                Log::debug("Cart add: items no es una Collection, inicializando");
            }
            
            $item = $this->items->where('id', $id)->first();
    
            if ($item) {
                $item->qty += $quantity;
                Log::debug("Cart add: incrementando cantidad de producto existente ID:{$id}");
            } else {
                $item = new \App\Models\CartItem($id, $name, $price, $quantity, $options);
                $this->items->push($item);
                Log::debug("Cart add: agregando nuevo producto ID:{$id}");
            }
    
            $this->recalculate();
            Session::put('cart', $this);
            return $this;
        } catch (\Exception $e) {
            Log::error("Error en Cart::add: " . $e->getMessage());
            throw $e;
        }
    }    public function update($id, $qty)
    {
        try {
            Log::debug("Cart update: Actualizando producto ID:{$id} a cantidad:{$qty}");
            
            $item = $this->items->where('id', $id)->first();
    
            if ($item) {
                $item->qty = $qty;
                Log::debug("Cart update: Producto encontrado y actualizado");
                $this->recalculate();
                Session::put('cart', $this);
                Log::debug("Cart update: Carrito recalculado y guardado en sesión");
            } else {
                Log::warning("Cart update: No se encontró el producto ID:{$id} en el carrito");
            }
    
            return $this;
        } catch (\Exception $e) {
            Log::error("Error en Cart::update: " . $e->getMessage());
            throw $e;
        }
    }

    public function remove($id)
    {
        $this->items = $this->items->reject(function ($item) use ($id) {
            return $item->id == $id;
        });

        $this->recalculate();
        Session::put('cart', $this);
        return $this;
    }

    public function clear()
    {
        $this->items = collect();
        $this->recalculate();
        Session::put('cart', $this);
        return $this;
    }    private function recalculate()
    {
        try {
            $this->total = 0;
            $this->items_count = 0;
    
            foreach ($this->items as $item) {
                if (is_object($item) && isset($item->price) && isset($item->qty)) {
                    $this->total += $item->price * $item->qty;
                    $this->items_count += $item->qty;
                    Log::debug("Cart recalculate: Producto ID:{$item->id}, Cantidad:{$item->qty}, Subtotal:" . ($item->price * $item->qty));
                } else {
                    Log::warning("Cart recalculate: Item inválido encontrado", ['item' => is_object($item) ? get_class($item) : gettype($item)]);
                }
            }
            
            Log::debug("Cart recalculate: Total final:{$this->total}, Items totales:{$this->items_count}");
        } catch (\Exception $e) {
            Log::error("Error en Cart::recalculate: " . $e->getMessage());
        }
    }
}
