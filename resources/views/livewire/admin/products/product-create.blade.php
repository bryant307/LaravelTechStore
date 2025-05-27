{{-- Componente livewire para crear un producto --}}
<div>

    <form wire:submit.prevent="store">
        <figure class="mb-4 relative">

            <div class="absolute top-8 right-8">
                <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer text-gray-600">
                    <i class="fas fa-camera mr-2"></i>
                    Actualizar Imagen
                    <input type="file" class="hidden" accept="image/*" wire:model="image">
                </label>
            </div>
            <img class="aspect-[16/9] object-cover object-center w-full"
                src="{{ $image ? $image->temporaryUrl() : asset('img/no-imagen.png') }}" 
                alt="">
        </figure>


        <x-validation-errors class="mb-4" />

        <div class="card">
            <div class="mb-4">
                <x-label class="mb-1">
                    Codigo
                </x-label>
                <x-input wire:model='product.sku' class="w-full" placeholder="Ingrese el codigo del producto" />
            </div>

            <div class="mb-4">
                <x-label class="mb-1">
                    Nombre
                </x-label>
                <x-input wire:model='product.name' class="w-full" placeholder="Ingrese el nombre del producto" />
            </div>

            <div class="mb-4">
                <x-label class="mb-1">
                    Descripcion
                </x-label>
                <x-textarea row="10" wire:model='product.description' class="w-full"
                    placeholder="Ingrese la descripcion del producto" />
            </div>

            <div class="mb-4">
                <x-label class="mb-1">
                    Familias
                </x-label>
                <x-select class="w-full" wire:model.live='family_id'>
                    <option value="" disabled>Seleccione una familia</option>
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}">
                            {{ $family->name }}
                        </option>
                    @endforeach
                </x-select>

                <div class="mb-4">
                    <x-label class="mb-1">
                        Categoria
                    </x-label>
                    <x-select class="w-full" wire:model.live='category_id'>
                        <option value="" disabled>Seleccione una Categoria</option>
                        @foreach ($this->categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <div class="mb-4">
                    <x-label class="mb-1">
                        Subcategoria
                    </x-label>
                    <x-select class="w-full" wire:model.live="product.subcategory_id">
                        <option value="" disabled>Seleccione una Subcategoria</option>
                        @foreach ($this->subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}">
                                {{ $subcategory->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <x-label class="mb-1">
                Precio
            </x-label>
            <x-input type="number" step="0.01" wire:model='product.price' class="w-full" placeholder="Ingrese el precio del producto" />
        </div>
        <div class="flex justify-end mt-4">
            <x-button>
                Crear Producto
            </x-button>
            

        </div>
    </form>
</div>
