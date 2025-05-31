<?php

namespace App\Livewire\Admin\Products;

use App\Models\Feature; // Asegúrate que este modelo exista si lo usas para pre-llenar
use App\Models\Option;
use App\Models\Product;
use App\Models\ProductVariant; // Necesitarás crear este modelo
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class ProductVariants extends Component
{
    use WithFileUploads;

    public Product $product;

    public $allOptions;

    public $variantForm = [
        'option_id' => '',
        'features' => [], // Aquí se listarán los features/valores para esta variante/opción
        // 'sku' => '', // Ejemplo si quieres SKU por variante
        // 'stock' => 0, // Ejemplo si quieres stock por variante
        // 'images' => [], // Para imágenes por variante
    ];

    public $openModal = false;

    // Reglas de validación para el formulario de variantes
    protected function rules()
    {
        return [
            'variantForm.option_id' => 'required|exists:options,id',
            'variantForm.features' => 'required|array|min:1',
            'variantForm.features.*.value' => 'required|string|max:255',
            'variantForm.features.*.description' => 'nullable|string|max:500',
            'variantForm.features.*.image' => 'nullable|image|max:2048',
            // 'variantForm.sku' => 'nullable|string|max:255|unique:product_variants,sku,' . ($this->editingVariantId ?? 'NULL') . ',id,product_id,' . $this->product->id, // Ejemplo validación SKU
            // 'variantForm.stock' => 'nullable|integer|min:0', // Ejemplo validación stock
        ];
    }

    // Mensajes de validación personalizados
    protected $validationAttributes = [
        'variantForm.option_id' => 'opción',
        'variantForm.features.*.value' => 'valor de característica',
        'variantForm.features.*.description' => 'descripción de característica',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        // Cargar opciones que quizás aún no están asignadas a este producto como variante
        $this->allOptions = Option::with('features')->get();
        $this->resetVariantForm();
    }

    // Propiedad computada para obtener la opción seleccionada (con su tipo)
    #[Computed]
    public function selectedOption()
    {
        if (!empty($this->variantForm['option_id'])) {
            // Asegúrate de que Option model tiene un accesor para 'type_description' si lo usas
            return Option::find($this->variantForm['option_id']);
        }
        return null;
    }

    // Se dispara cuando cambia variantForm.option_id
    public function updatedVariantFormOptionId($value)
    {
        // Al cambiar la opción, precargar los features predefinidos de esa opción
        $option = Option::with('features')->find($value);
        $this->variantForm['features'] = [];
        if ($option && $option->features->count() > 0) {
            foreach ($option->features as $feature) {
                $this->variantForm['features'][] = [
                    'value' => $feature->value,
                    'description' => $feature->description,
                ];
            }
        } else {
            // Si no hay features predefinidos, dejar vacío para que el usuario agregue
            $this->variantForm['features'] = [];
        }
    }

    public function addVariantFeature()
    {
        $this->variantForm['features'][] = [
            'value' => '',
            'description' => '',
            'image' => null, // Nuevo campo para la imagen
        ];
    }

    public function removeVariantFeature($index)
    {
        unset($this->variantForm['features'][$index]);
        $this->variantForm['features'] = array_values($this->variantForm['features']);
        // Opcional: si quieres asegurar que siempre haya al menos un campo
        // if (empty($this->variantForm['features']) && !empty($this->variantForm['option_id'])) {
        //     $this->addVariantFeature();
        // }
    }

    public function saveVariant()
    {
        try {
            $this->validate();

            $optionId = $this->variantForm['option_id'];
            $option = Option::find($optionId);

            \Log::info('Iniciando guardado de variantes', [
                'option_id' => $optionId,
                'features' => $this->variantForm['features']
            ]);

            foreach ($this->variantForm['features'] as $index => $featureData) {
                if (empty($featureData['value'])) continue;

                $imagePath = null;
                if (!empty($featureData['image'])) {
                    try {
                        \Log::info('Datos de imagen encontrados', [
                            'index' => $index,
                            'image_type' => gettype($featureData['image']),
                            'is_temp_file' => $featureData['image'] instanceof \Livewire\TemporaryUploadedFile
                        ]);

                        if ($featureData['image'] instanceof \Livewire\TemporaryUploadedFile) {
                            $originalName = $featureData['image']->getClientOriginalName();
                            $extension = $featureData['image']->getClientOriginalExtension();
                            $newFilename = time() . '_' . uniqid() . '.' . $extension;
                            
                            $imagePath = $featureData['image']->storeAs('variants', $newFilename, 'public');
                            
                            if ($imagePath) {
                                \Log::info('Imagen guardada exitosamente', [
                                    'original_name' => $originalName,
                                    'new_name' => $newFilename,
                                    'path' => $imagePath,
                                    'full_url' => \Storage::disk('public')->url($imagePath)
                                ]);
                            } else {
                                \Log::error('Falló el guardado de la imagen');
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error procesando imagen', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        continue;
                    }
                }

                try {
                    $variant = ProductVariant::create([
                        'product_id' => $this->product->id,
                        'option_id' => $optionId,
                        'value' => $featureData['value'],
                        'description' => $featureData['description'] ?? null,
                        'image_path' => $imagePath
                    ]);

                    \Log::info('Variante creada exitosamente', [
                        'id' => $variant->id,
                        'value' => $variant->value,
                        'image_path' => $variant->image_path
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error al crear variante', [
                        'error' => $e->getMessage(),
                        'data' => [
                            'product_id' => $this->product->id,
                            'option_id' => $optionId,
                            'value' => $featureData['value'],
                            'image_path' => $imagePath
                        ]
                    ]);
                    throw $e;
                }
            }

            $this->product->load('productVariants.option');
            $this->dispatch('notify', [
                'message' => 'Variantes guardadas exitosamente',
                'type' => 'success'
            ]);
            $this->closeModal();

        } catch (\Exception $e) {
            \Log::error('Error general en saveVariant', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify', [
                'message' => 'Error al guardar las variantes: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function openModalForm()
    {
        $this->resetVariantForm();
        // Por defecto, si hay opciones, preseleccionar la primera y añadir un feature
        if ($this->allOptions->count() > 0) {
             //$this->variantForm['option_id'] = $this->allOptions->first()->id; // Opcional: preseleccionar
             // $this->updatedVariantFormOptionId($this->variantForm['option_id']); // Disparar actualización
        } else {
            // Manejar caso sin opciones disponibles
             $this->dispatch('notify', ['message' => 'No hay opciones disponibles para crear variantes.', 'type' => 'warning']);
             return;
        }
        $this->openModal = true;
    }

    public function closeModal()
    {
        $this->openModal = false;
        $this->resetVariantForm();
    }

    private function resetVariantForm()
    {
        $this->resetErrorBag(); // Limpiar errores de validación
        $this->variantForm = [
            'option_id' => '',
            'features' => [],
            // 'sku' => '',
            // 'stock' => 0,
        ];
    }
    
    // Método para eliminar todas las variantes de una opción para un producto
    public function deleteVariantsByOption($optionId)
    {
        ProductVariant::where('product_id', $this->product->id)
                      ->where('option_id', $optionId)
                      ->delete();
        $this->product->load('productVariants.option'); // Recargar
        $this->dispatch('notify', ['message' => 'Variantes eliminadas exitosamente.', 'type' => 'success']);
    }


    public function render()
    {
        // Cargar variantes agrupadas para la vista
        // Es importante el `load` aquí para asegurar que los datos están frescos, especialmente después de CUD.
        $this->product->load('productVariants.option', 'productVariants.feature'); // Asumiendo relación 'feature' si enlazas a Feature global

        $groupedVariants = $this->product->productVariants->groupBy('option.name');
        
        return view('livewire.admin.products.product-variants', [
            'groupedVariants' => $groupedVariants
        ]);
    }
}
