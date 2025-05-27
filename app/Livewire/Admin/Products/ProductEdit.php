<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Family;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Product; 

class ProductEdit extends Component
{
    use WithFileUploads;

    public $product;

    public $productEdit;

    public $families;
    public $family_id = '';
    public $category_id = '';

    public $image;

    public function mount($product)
    {

        $this->productEdit = $product->only('sku', 'name', 'description', 'image_path', 'price', 'subcategory_id');

        $this->families = Family::all();

        $this->category_id = $product->subcategory->category->id;
        $this->family_id = $product->subcategory->category->family_id;
    }

    public function boot()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                if ($this->family_id === '') {
                    $validator->errors()->add('family_id', 'El campo familia es obligatorio.');
                }
                if ($this->category_id === '') {
                    $validator->errors()->add('category_id', 'El campo categoría es obligatorio.');
                }
            });
        });
    }

    public function updatedFamilyId($value)
    {
        $this->category_id = '';
        $this->productEdit['subcategory_id'] = '';
    }

    public function updatedCategoryId($value)
    {
        $this->productEdit['subcategory_id'] = '';
    }

    #[Computed()]
    public function categories()
    {
        return Category::where('family_id', $this->family_id)->get();
    }

    #[Computed()]
    public function subcategories()
    {
        return Subcategory::where('category_id', $this->category_id)->get();
    }

    public function store()
    {
        $this->validate([
            'image' => 'nullable|image|max:1024',
            'productEdit.sku' => [
                'required',
                Rule::unique('products', 'sku')->ignore($this->product->id),
            ],
            'productEdit.name' => 'required|max:255',
            'productEdit.description' => 'nullable',
            'productEdit.price' => 'required|numeric|min:0',
            'productEdit.subcategory_id' => 'required|exists:subcategories,id',
        ]);
        if ($this->image) {

            Storage::delete($this->productEdit['image_path']);

            $this->productEdit['image_path'] = $this->image->store('products');
        }

        $this->product->update($this->productEdit);

        session('swal', [
            'icon' => 'success',
            'title' => 'Producto actualizado con éxito',
            'text' => 'El producto ha sido actualizado correctamente.',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.products.product-edit');
    }
}
