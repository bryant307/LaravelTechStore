<?php

namespace App\Livewire\Admin\Options;

use Livewire\Component;

class AddNewFeature extends Component
{

    public $option;
    public $newFeatures = [
        [
            'value' => '',
            'description' => ''
        ],
    ];

    protected $listeners = ['featureAdded' => 'resetInputs'];

    public function render()
    {
        return view('livewire.admin.options.add-new-feature');
    }

    public function addFeature()
    {
        $this->dispatch('addFeatureToOption', $this->option['id'], $this->newFeatures[0]);
    }


    public function resetInputs($optionId = null)
    {
        $this->newFeatures = [
            [
                'value' => '',
                'description' => ''
            ]
        ];
    }
}
