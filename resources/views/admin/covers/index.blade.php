<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Portadas',
        'route' => route('admin.covers.index'),
    ],
]">

    <x-slot name="action">
        <a href="{{ route('admin.covers.create') }}" class="btn btn-blue">
            Nuevo
        </a>

    </x-slot>

    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            <span class="text-blue-700 text-sm">Arrastra las portadas por el icono <i class="fas fa-grip-vertical"></i> para cambiar su orden de visualización.</span>
        </div>
    </div>
    <ul id="sortable-covers" class="space-y-4">
        @foreach ($covers as $cover)
            <li data-id="{{$cover->id}}" class="bg-white rounded-lg shadow-lg overflow-hidden lg:flex space-y-3 lg:space-y-0 lg:space-x-4 sortable-item">
                <!-- Drag handle -->
                <div class="flex items-center justify-center w-8 bg-gray-100 cursor-move sortable-handle">
                    <i class="fas fa-grip-vertical text-gray-400"></i>
                </div>
                <img src="{{$cover->image}}" class="w-64 aspect-[3/1] object-cover object-center" alt="">
                <div class="p-4 flex-1 flex justify-between items-center">
                    <div>
                        <h1 class="font-semibold ">
                            {{$cover->title}}
                        </h1>
                        <p>
                            @if ($cover->is_active)
                                <span>
                                    Activo
                                </span>
                            @else
                                <span class="text-red-500">
                                    Inactivo
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-bold">
                            Fecha de Inicio
                        </p>
                        <p>
                            {{$cover->start_at}}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-bold">
                            Fecha de Fin
                        </p>
                        <p>
                            {{$cover->end_at ? $cover->end_at : 'No definida'}}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('admin.covers.edit', $cover) }}" class="btn btn-blue">
                            Editar
                        </a>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <style>
            .sortable-item {
                transition: all 0.3s ease;
            }
            
            .sortable-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }
            
            .sortable-handle {
                transition: all 0.2s ease;
            }
            
            .sortable-handle:hover {
                background-color: #f3f4f6;
            }
            
            .sortable-item.sortable-ghost {
                opacity: 0.5;
                background-color: #f9f9f9;
            }
            
            .sortable-item.sortable-chosen {
                transform: rotate(3deg);
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sortableElement = document.getElementById('sortable-covers');
                
                if (sortableElement) {
                    const sortable = Sortable.create(sortableElement, {
                        handle: '.sortable-handle',
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        onEnd: function(evt) {
                            const covers = Array.from(sortableElement.children).map((item, index) => ({
                                id: parseInt(item.dataset.id),
                                order: index + 1
                            }));

                            // Enviar la nueva orden al servidor
                            fetch('{{ route("admin.covers.update-order") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    covers: covers
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    // Mostrar notificación de éxito
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Orden actualizado',
                                        text: 'El orden de las portadas se ha actualizado correctamente.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un error al actualizar el orden de las portadas.'
                                });
                            });
                        }
                    });
                }
            });
        </script>
    @endpush

</x-admin-layout>
