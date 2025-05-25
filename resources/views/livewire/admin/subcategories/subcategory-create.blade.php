<form>
    <div class="card">

        <x-validation-errors class="mb-4" />

        <div class="mb-4">
            <x-label class="mb-2">
                Familias
            </x-label>

            <x-select name="family_id" class="w-full">
                @foreach ($families as $family)
                    <option value="{{ $family->id }}" @selected(old('family_id') == $family->id)>
                        {{ $family->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
        {{-- <div class="mb-4">
            <x-label class="mb-2">
                Seleccione la Categoria
            </x-label>
            <x-select name="category_id" class="w-full">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-select>

        </div> --}}

        <div class="mb-4">
            <x-label class="mb-2">
                Nombre
            </x-label>
            <x-input class="w-full" placeholder="Ingrese el nombre de la categoria" />

        </div>
        <div class="flex justify-end">
            <x-button class="btn btn-blue">
                Guardar
            </x-button>
        </div>

    </div>
</form>
