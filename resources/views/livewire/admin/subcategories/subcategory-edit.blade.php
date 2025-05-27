<div>
     <form wire:submit="save">
         <div class="card">

             <x-validation-errors class="mb-4" />

             <div class="mb-4">
                 <x-label class="mb-2">
                     Familias
                 </x-label>



                 <x-select name="family_id" class="w-full" wire:model="subcategoryEdit.family_id">

                     <option value="" disabled>
                         Seleccione una familia
                     </option>
                     @foreach ($families as $family)
                         <option value="{{ $family->id }}" @selected(old('family_id') == $family->id)>
                             {{ $family->name }}
                         </option>
                     @endforeach
                 </x-select>
             </div>
             <div class="mb-4">
                 <x-label class="mb-2">
                     Seleccione la Categoria
                 </x-label>
                 <x-select name="category_id" class="w-full" wire:model="subcategoryEdit.category_id">
                     <option value="" disabled>
                         Sellecione una Subcategoria
                     </option>

                     @foreach ($this->categories as $category)
                         <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                             {{ $category->name }}
                         </option>
                     @endforeach
                 </x-select>

             </div>

             <div class="mb-4">
                 <x-label class="mb-2">
                     Nombre
                 </x-label>
                 <x-input class="w-full" placeholder="Ingrese el nombre de la categoria"
                     wire:model="subcategoryEdit.name" />

             </div>
             <div class="flex justify-end">

                 <x-danger-button onclick="confirmDelete()">
                     Eliminar
                 </x-danger-button>
                 <x-button class="btn btn-blue">
                     Actualizar
                 </x-button>
             </div>

         </div>
     </form>

     <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" id="delete-form">
         @csrf
         @method('DELETE')
     </form>


     @push('js')
         <script>
             function confirmDelete() {
                 // 
                 Swal.fire({
                     title: "Estas seguro?",
                     text: "Se elimina definitivamente la categoria",
                     icon: "warning",
                     showCancelButton: true,
                     confirmButtonColor: "#3085d6",
                     cancelButtonColor: "#d33",
                     confirmButtonText: "Si!",
                     cancelButtonText: "No"
                 }).then((result) => {
                     if (result.isConfirmed) {
                         Swal.fire({
                             title: "Categoria eliminada",
                             text: "La categoria ha sido eliminada.",
                             icon: "success"
                         });

                         document.getElementById('delete-form').submit();
                     }
                 });
             }
         </script>
     @endpush



 </div>
