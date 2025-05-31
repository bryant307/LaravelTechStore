<div>
    <section class="mb-6 rounded-lg bg-white border border-gray-200 shadow-lg">
        <header class="border-b border-gray-100 px-6 py-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <h1 class="text-xl font-semibold text-gray-800 mb-2 sm:mb-0">
                    Variantes del Producto: <span class="text-indigo-600">{{ $product->name }}</span>
                </h1>
                <x-button wire:click="openModalForm" color="indigo">
                    <i class="fas fa-plus mr-2"></i>
                    Agregar Grupo de Variantes
                </x-button>
            </div>
        </header>

        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Variantes Configuradas:</h2>
            @if($groupedVariants->count() > 0)
                <div class="space-y-6">
                    @foreach($groupedVariants as $optionName => $variants)
                        <div class="p-4 border border-gray-200 rounded-md shadow-sm bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-md font-semibold text-gray-700">
                                    Opción: <span class="text-blue-600">{{ $optionName ?: 'Sin Opción Definida' }}</span>
                                </h3>
                                <button wire:click="deleteVariantsByOption({{ $variants->first()->option_id }})" 
                                        wire:confirm="¿Estás seguro de que quieres eliminar todas las variantes para la opción '{{ $optionName }}'?"
                                        class="text-red-500 hover:text-red-700 text-sm">
                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar Grupo
                                </button>
                            </div>
                            <ul class="list-disc list-inside ml-4 space-y-1">
                                @foreach($variants as $variant)
                                    <li class="text-sm text-gray-600 flex items-center gap-2">
                                        @if($variant->image_path)
                                            <div class="w-8 h-8 relative">
                                                <img src="{{ Storage::disk('public')->url($variant->image_path) }}" 
                                                     alt="Imagen de {{ $variant->value }}" 
                                                     class="w-full h-full object-cover rounded-md border border-gray-200"
                                                     onerror="this.onerror=null; this.src='{{ asset('img/no-imagen.png') }}';">
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Sin imagen</span>
                                        @endif
                                        <span class="font-medium">{{ $variant->value }}</span>
                                        @if($variant->description)
                                            <span class="text-gray-500 italic">({{ $variant->description }})</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2zm3-12V3m0 18v-2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sin variantes definidas</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Este producto aún no tiene variantes configuradas.
                    </p>
                    <div class="mt-6">
                         <x-button wire:click="openModalForm" color="indigo">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Grupo de Variantes
                        </x-button>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <x-dialog-modal wire:model.live="openModal" maxWidth="2xl">
        <x-slot name="title">
            <h2 class="text-xl font-semibold text-gray-800">
                Agregar/Editar Valores para una Opción
            </h2>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-6 p-1">
                <div>
                    <x-label for="option_id" class="mb-1 font-medium text-gray-700">Opción Base</x-label>
                    <x-select id="option_id" class="w-full" wire:model.live="variantForm.option_id">
                        <option value="" disabled>Selecciona una opción...</option>
                        @forelse ($allOptions as $option)
                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                        @empty
                            <option value="" disabled>No hay opciones disponibles</option>
                        @endforelse
                    </x-select>
                    @error('variantForm.option_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                @if ($this->selectedOption)
                    <div class="p-3 bg-indigo-50 rounded-md border border-indigo-200">
                        <p class="text-sm text-indigo-700">
                            Configurando valores para la opción: <strong class="font-semibold">{{ $this->selectedOption->name }}</strong>
                            (Tipo:
                            <span class="font-semibold">
                                @switch($this->selectedOption->type)
                                    @case(1) Texto @break
                                    @case(2) Color @break
                                    @default Desconocido @break
                                @endswitch
                            </span>)
                        </p>
                         @if($this->selectedOption->features->count() > 0)
                            <p class="text-xs text-gray-500 mt-1">Valores predefinidos para "{{ $this->selectedOption->name }}":
                                @foreach($this->selectedOption->features as $f)
                                    <span class="inline-block bg-gray-200 rounded px-2 py-0.5 text-xs mr-1 my-1">{{ $f->value }}</span>
                                @endforeach
                            </p>
                        @endif
                    </div>
                @endif

                {{-- Campos para SKU y Stock generales para este grupo de variantes (opcional) --}}
                {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="variant_sku" class="mb-1 font-medium text-gray-700">SKU (Opcional para el grupo)</x-label>
                        <x-input id="variant_sku" class="w-full" placeholder="Ej: PROD-VAR-001" wire:model.defer="variantForm.sku" />
                        @error('variantForm.sku') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <x-label for="variant_stock" class="mb-1 font-medium text-gray-700">Stock (Opcional para el grupo)</x-label>
                        <x-input type="number" id="variant_stock" class="w-full" placeholder="Ej: 100" wire:model.defer="variantForm.stock" />
                        @error('variantForm.stock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div> --}}


                @if($variantForm['option_id'])
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-3">
                             <h3 class="text-md font-semibold text-gray-700">Valores Específicos:</h3>
                             <x-button type="button" wire:click="addVariantFeature" variant="outline" size="sm">
                                <i class="fas fa-plus mr-1"></i> Agregar Valor
                            </x-button>
                        </div>
                       
                        @if (empty($variantForm['features']))
                            <p class="text-sm text-gray-500 text-center py-3">Aún no has agregado valores para esta opción. Haz clic en "Agregar Valor".</p>
                        @else
                            <ul class="space-y-5">
                                @foreach ($variantForm['features'] as $index => $featureForm)
                                    <li wire:key="variant-feature-item-{{ $index }}" class="relative border border-gray-300 rounded-lg p-4 pt-6 bg-white shadow-sm">
                                        <div class="absolute top-2 right-2">
                                            <button type="button" wire:click="removeVariantFeature({{ $index }})" class="text-gray-400 hover:text-red-600 transition-colors">
                                                <i class="fas fa-times-circle fa-lg"></i>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-3">
                                            <div class="md:col-span-1">
                                                <x-label for="feature_value_{{ $index }}" class="text-sm font-medium text-gray-600">Valor <span class="text-red-500">*</span></x-label>
                                                @if ($this->selectedOption && $this->selectedOption->type == 2) {{-- Tipo Color --}}
                                                    <input type="color" id="feature_value_{{ $index }}" wire:model="variantForm.features.{{ $index }}.value" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 p-1">
                                                @else {{-- Tipo Texto u otro --}}
                                                    <x-input id="feature_value_{{ $index }}" class="w-full mt-1" placeholder="Ej: Rojo, XL, Algodón" wire:model="variantForm.features.{{ $index }}.value" />
                                                @endif
                                                @error("variantForm.features.{$index}.value") <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="md:col-span-1">
                                                <x-label for="feature_description_{{ $index }}" class="text-sm font-medium text-gray-600">Descripción (Opcional)</x-label>
                                                <x-input id="feature_description_{{ $index }}" class="w-full mt-1" placeholder="Detalles adicionales" wire:model="variantForm.features.{{ $index }}.description" />
                                                @error("variantForm.features.{$index}.description") <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="md:col-span-2">
                                                <x-label for="feature_image_{{ $index }}" class="text-sm font-medium text-gray-600">Imagen (Opcional)</x-label>
                                                <input type="file" id="feature_image_{{ $index }}" 
                                                    wire:model="variantForm.features.{{ $index }}.image" 
                                                    class="w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                                                    accept="image/*">
                                                @error("variantForm.features.{$index}.image") <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                                
                                                @if(isset($featureForm['image']))
                                                    <div class="mt-2">
                                                        @if(is_string($featureForm['image']))
                                                            <img src="{{ Storage::disk('public')->url($featureForm['image']) }}" alt="Preview" class="h-20 w-20 object-cover rounded-lg border">
                                                        @elseif($featureForm['image'] instanceof \Livewire\TemporaryUploadedFile)
                                                            <img src="{{ $featureForm['image']->temporaryUrl() }}" alt="Preview" class="h-20 w-20 object-cover rounded-lg border">
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                         @error('variantForm.features') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <x-secondary-button wire:click="closeModal">
                    Cancelar
                </x-secondary-button>
                <x-button wire:click="saveVariant" wire:loading.attr="disabled" wire:target="saveVariant" color="indigo">
                    <span wire:loading wire:target="saveVariant">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Guardando...
                    </span>
                    <span wire:loading.remove wire:target="saveVariant">
                        <i class="fas fa-save mr-2"></i> Guardar Valores
                    </span>
                </x-button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
