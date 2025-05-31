<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            [
                'name' => 'Talla',
                'type' => 1,
                'features' => [
                    [
                        'value' => 'S',
                        'description' => 'Small'
                    ],
                    [
                        'value' => 'M',
                        'description' => 'Medium'
                    ],
                    [
                        'value' => 'L',
                        'description' => 'Large'
                    ],
                    [
                        'value' => 'XL',
                        'description' => 'Extra Large'
                    ],
                ]
            ],
            [
                'name' => 'color',
                'type' => 2,
                'features' => [
                    [
                        'value' => '#FF0000',
                        'description' => 'Red'
                    ],
                    [
                        'value' => '#0000FF',
                        'description' => 'Blue'
                    ],
                    [
                        'value' => '#00FF00',
                        'description' => 'Green'
                    ],
                    [
                        'value' => '#FFFF00',
                        'description' => 'Yellow'
                    ]
                ]
            ],
            [
                'name' => 'Sexo',
                'type' => 1,
                'features' => [
                    [
                        'value' => 'H',
                        'description' => 'Hombre'
                    ],
                    [
                        'value' => 'M',
                        'description' => 'Mujer'
                    ],
                    [
                        'value' => 'U',
                        'description' => 'Unisex'
                    ],
                ]
            ],
        ];

        foreach ($options as $option) {
            $optionModel = Option::create([
                'name' => $option['name'],
                'type' => $option['type']
            ]);

            foreach ($option['features'] as $feature) {
                $optionModel->features()->create([
                    'value' => $feature['value'],
                    'description' => $feature['description'],
                ]);
            }
        }
    }
}
