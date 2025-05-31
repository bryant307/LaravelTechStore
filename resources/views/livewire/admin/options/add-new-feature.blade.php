<div>
    {{-- Success is as dangerous as failure. --}}
    <div class="flex">
        <div class="flex-1">
            <div>
                <x-label class="mb-2">
                    Valor
                </x-label>
                @if ($option['type'] == 1)
                    <x-input wire:model="newFeatures.0.value" class="w-full" placeholder="Ingresar valor" />
                @elseif ($option['type'] == 2)
                    <input type="color" wire:model="newFeatures.0.value">
                @endif
            </div>
        </div>
        <div class="flex-1">
            <div>
                <x-label class="mb-2">
                    Descripción
                </x-label>
                <x-input wire:model="newFeatures.0.description" class="w-full" placeholder="Ingresar descripción" />
            </div>
        </div>
        <div class="flex items-end ml-2">
            <x-button wire:click="addFeature" class="btn btn-blue">
                Agregar
            </x-button>
        </div>
    </div>
</div>
