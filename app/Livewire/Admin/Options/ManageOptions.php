<?php

namespace App\Livewire\Admin\Options;

use App\Models\Option;
use Livewire\Component;

class ManageOptions extends Component
{

    public $options;
    public $newOption =
        [
            'name' => '',
            'type' => 1,
            'features' => [
                [
                    'value' => '',
                    'description' => ''
                ],
            ],
        ];
    public $openModal = false;
    public function mount()
    {
        $this->options = Option::with('features')->get();
    }

    public function addFeature()
    {
        $this->newOption['features'][] = [
            'value' => '',
            'description' => ''
        ];
    }
    // Renombrar el método para el modal de creación
    public function removeFeatureInput($index)
    {
        unset($this->newOption['features'][$index]);
        $this->newOption['features'] = array_values($this->newOption['features']);
    }

    public function addOption()
    {
        $this->validate([
            'newOption.name' => 'required',
            'newOption.type' => 'required|in:1,2',
            'newOption.features' => 'required|array|min:1',
        ]);

        foreach ($this->newOption['features'] as $index => $feature) {
            if ($this->newOption['type'] == 1) {
                $rules['newOption.features.' . $index . '.value'] = 'required';
            } else {
            $rules['newOption.features.' . $index . '.description'] = 'required|regex:/^[a-zA-Z0-9\s]+$/';
            }
            $rules['newOption.features.' . $index . '.description'] = 'required';

        }
        $this->validate($rules);
        $option = Option::create([
            'name' => $this->newOption['name'],
            'type' => $this->newOption['type'],
        ]);
        foreach ($this->newOption['features'] as $feature) {
            $option->features()->create([
                'value' => $feature['value'],
                'description' => $feature['description'],
            ]);
        }
        $this->reset('newOption');
        $this->reset('openModal');
        $this->dispatch('browser:swal', [
            'icon' => 'success',
            'title' => 'Opción creada con éxito',
            'text' => 'La opción ha sido creada correctamente.',
        ]);
    }
    public function getListeners()
    {
        return [
            'addFeatureToOption' => 'addFeatureToOption',
            'removeFeature' => 'removeFeature',
        ];
    }

    public function updated($property)
    {
        // Forzar refresco del input en el hijo si es necesario
    }

    public function addFeatureToOption($optionId, $feature)
    {
        $option = Option::find($optionId);
        if ($option && !empty($feature['value']) && !empty($feature['description'])) {
            $option->features()->create([
                'value' => $feature['value'],
                'description' => $feature['description'],
            ]);
            $this->dispatch('browser:swal', [
                'icon' => 'success',
                'title' => 'Feature agregado',
                'text' => 'El feature fue agregado correctamente.'
            ]);
            $this->options = Option::with('features')->get();
            // Forzar refresco del componente hijo
            $this->dispatch('featureAdded', id: $optionId, to: 'add-new-feature.' . $optionId);
        }
    }
    // Mantener este método para eliminar features de la base de datos
    public function removeFeature($optionId, $featureId)
    {
        $option = Option::find($optionId);
        if ($option) {
            $option->features()->where('id', $featureId)->delete();
            $this->options = Option::with('features')->get();
            $this->dispatch('browser:swal', [
                'icon' => 'success',
                'title' => 'Feature eliminado',
                'text' => 'El feature fue eliminado correctamente.'
            ]);
        }
    }

    public function removeOption($optionId)
    {
        $option = Option::find($optionId);
        if ($option) {
            $option->features()->delete();
            $option->delete();
            $this->options = Option::with('features')->get();
            $this->dispatch('browser:swal', [
                'icon' => 'success',
                'title' => 'Opción eliminada',
                'text' => 'La opción ha sido eliminada correctamente.',
            ]);
        }
    }
    public function render()
    {
        return view('livewire.admin.options.manage-options');
    }
}
