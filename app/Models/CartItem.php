<?php

namespace App\Models;

class CartItem
{
    public $id;
    public $name;
    public $price;
    public $qty;
    public $options;

    public function __construct($id, $name, $price, $qty = 1, $options = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->qty = $qty;
        $this->options = $options;
    }

    /**
     * Prepara el objeto para la serialización.
     *
     * @return array
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'qty' => $this->qty,
            'options' => $this->options
        ];
    }

    /**
     * Restaura las propiedades del objeto después de la deserialización.
     *
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->qty = $data['qty'];
        $this->options = $data['options'];
    }
}
