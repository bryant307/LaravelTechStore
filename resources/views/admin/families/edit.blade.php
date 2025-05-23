{{-- View para editar las categorías --}}
<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Familias',
        'route' => route('admin.families.index'),
    ],
    [
        'name' => $family->name,
    ],
]">

    <div class="card">
        <form action="{{ route('admin.families.update', $family) }}" method="POST">

            @csrf

            @method('PUT')

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>
                <x-input class="w-full" placeholder="Ingrese el nombre de la categoria" name="name"
                    value="{{ old('name', $family->name) }}" />

            </div>

            <div class="flex justify-end space-x-2">

                <x-danger-button onclick="confirmDelete()">
                    Eliminar
                </x-danger-button>

                <x-button class=" ml-2 btn btn-blue">
                    Actualizar
                </x-button>
            </div>

        </form>

    </div>

    <form action="{{ route('admin.families.destroy', $family) }}" method="POST" id="delete-form">
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

</x-admin-layout>
