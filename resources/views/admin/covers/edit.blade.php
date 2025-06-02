<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
        'route' => route('admin.covers.index'),

    ],
    [
        'name' => 'Editar Portada',

    ]
]">

<form action="{{ route('admin.covers.update', $cover) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <figure class="relative mb-4">
            <div class="absolute top-8 right-8">
                <label class="flex items-center px-4 py-2 rounded-lg bg-white cursor-pointer text-gray-600">
                    <i class="fas fa-camera mr-2"></i>
                    Actualizar Imagen
                    <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(event, '#imgPreview')">
                </label>
            </div>
            <img src="{{ asset($cover->image) }}" alt="Portada"
                class="w-full aspect-[3/1] object-cover object-center" id="imgPreview">
        </figure>

        <x-validation-errors class="mb-4">

        </x-validation-errors>



        <div class="mb-4">
            <x-label class="mb-1">
                Titulo
            </x-label>
            <x-input name="title" value="{{ old('title', $cover->title) }}" class="w-full"
                placeholder="Ingrese el titulo de la portada" required />

        </div>
        <div class="mb-4">
            <x-label>
                Fecha de Inicio
            </x-label>
            <x-input type="datetime-local" name="start_at" value="{{ old('start_at', $cover->start_at)}}" class="w-full"
                required />
        </div>

        <div class="mb-4">
            <x-label>
                Fecha de Fin (opcional)
            </x-label>
            <x-input type="datetime-local" name="end_at" value="{{ old('end_at', $cover->end_at ? $cover->end_at : '')  }}" class="w-full" />
        </div>

        <div class="mb-4 flex space-x-2">
            <label>
                <x-input type="radio" name="is_active" value="1" :checked="$cover->is_active == 1"/>
                Activo
            </label>
            <label>
                <x-input type="radio" name="is_active" value="0" :checked="$cover->is_active == 0" />
                Inactivo
            </label>
        </div>

        <div class="flex justify-end">
            <x-button>
                Actualizar portada
            </x-button>
        </div>
    </form>

    @push('js')
        <script>
            function previewImage(event, querySel) {
                // Recuperamos el input que desencadenó la acción
                const input = event.target;

                // Recuperamos la etiqueta img donde cargaremos la imagen
                const imgPreview = document.querySelector(querySel);

                // Verificamos si existe una imagen seleccionada
                if (!input.files.length) return;

                // Recuperamos el archivo subido
                const file = input.files[0];

                // Verificamos que sea una imagen
                if (!file.type.startsWith('image/')) {
                    alert('Por favor selecciona una imagen válida');
                    return;
                }

                // Creamos la url
                const objectURL = URL.createObjectURL(file);

                // Modificamos el atributo src de la etiqueta img
                imgPreview.src = objectURL;

                // Limpiamos la URL creada cuando la imagen se carga
                imgPreview.onload = () => {
                    URL.revokeObjectURL(objectURL);
                };

            }
        </script>
    @endpush
</x-admin-layout>