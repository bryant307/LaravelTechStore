<div>
    <section class="rounded-lg bg-white shadow-lg">
        <header class="border-b border-gray-100 px-6 py-3">
            <div class="flex justify-between">
                <h1 class="text-lg font-semibold text-gray-700">
                    Opciones
                </h1>

                <x-button wire:click="$set('openModal', true)">
                    <i class="fas fa-plus"></i>
                    </i>
                    Agregar opciones
                </x-button>
            </div>
        </header>
        <div class="p-6 ">

            <div class="space-y-6">

                @foreach ($options as $option)
                    <div class="p-6 rounded-lg border border-gray-200 relative" wire:key="option-{{ $option->id }}">
                        <div class="absolute -top-3 bg-white px-4">

                            <button>
                                <i class="fas fa-trash text-red-500 hover:text-red-700"
                                    wire:click="removeOption({{ $option->id }})"></i>
                            </button>
                            <span>
                                {{ $option->name }}
                            </span>

                        </div>
                        <div class="flex flex-wrap mb-4">
                            {{-- valores --}}

                            @foreach ($option->features as $feature)
                                @switch($option->type)
                                    @case(1)
                                        <span
                                            class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-400 border border-gray-500 flex items-center gap-2">
                                            {{ $feature->description }}
                                            <button wire:click="removeFeature({{ $option->id }}, {{ $feature->id }})">
                                                <i class="fas fa-times text-red-500 hover:text-red-700"></i>
                                            </button>
                                        </span>
                                    @break

                                    @case(2)
                                        <span
                                            class="inline-block h-6 w-6 shadow-lg rounded-full border-2 border-gray-300 mr-4 relative"
                                            style="background-color: {{ $feature->value }}">
                                            <button wire:click="removeFeature({{ $option->id }}, {{ $feature->id }})">
                                                <i class="fas fa-times text-red-500 hover:text-red-700 text-xs"></i>
                                            </button>
                                        </span>
                                    @break

                                    @default
                                @endswitch
                            @endforeach
                        </div>

                        <div>
                            @livewire('admin.options.add-new-feature', ['option' => $option], key('add-new-feature.' . $option->id))
                        </div>


                    </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- Componente x-modal --}}
    <x-dialog-modal wire:model="openModal" maxWidth="2xl">
        <x-slot name="title">
            Crear una Nueva Opción
        </x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <x-label class="mb-2">
                        Nombre
                    </x-label>
                    <x-input wire:model="newOption.name" class="w-full" placeholder="Por ejemplo: Color o Tamno">

                    </x-input>
                </div>
                <div>
                    <x-label class="mb-2">
                        Tipo de Opción
                    </x-label>

                    <x-select wire:model="newOption.type" class="w-full">
                        <option value="1">Texto</option>
                        <option value="2">Color</option>
                    </x-select>
                </div>
            </div>

            <div class="flex">
                <hr class="flex-1 items-center mb-4">

                <span class="mx-2">
                    Valores
                </span>

                <hr class="flex-1">
            </div>
            <div class="mb-4">
                @foreach ($newOption['features'] as $index => $feature)
                    <div class="p-6 rounded-lg border border-gray-200 relative" wire:key="feature-{{ $index }}">
                        <div class="absolute -top-3 bg-white px-4">
                            <x-button wire:click="removeFeatureInput({{ $index }})" class="text-red-500">
                                <i class="fas fa-trash text-red-500"></i>
                            </x-button>
                        </div>
                        <div class="grid grid-cols-2">
                            <div>
                                <x-label class="mb-2">
                                    Valor
                                </x-label>
                                @if ($newOption['type'] == 1)
                                    <x-input wire:model="newOption.features.{{ $index }}.value" class="w-full"
                                        placeholder="Ingresar valor" />
                                @elseif ($newOption['type'] == 2)
                                    <input type="color" wire:model="newOption.features.{{ $index }}.value">
                                @endif
                            </div>
                            <div>
                                <x-label class="mb-2">
                                    Descripcion
                                </x-label>
                                <x-input wire:model="newOption.features.{{ $index }}.description" class="w-full"
                                    placeholder="Ingresar descripcion" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end">
                <x-button wire:click="addFeature" class="mb-4">
                    <i>
                        <i class="fas fa-plus"></i>
                    </i>
                    Agregar Valor
                </x-button>

            </div>
        </x-slot>
        <x-slot name="footer">

            <button class="btn btn-blue" wire:click="addOption">
                <i>
                    <i class="fas fa-save"></i>
                </i>
                Guardar

            </button>

        </x-slot>
    </x-dialog-modal>
</div>
