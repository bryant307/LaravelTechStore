<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking database data...');
        
        $families = \App\Models\Family::count();
        $this->info("Families: {$families}");
        
        $options = \App\Models\Option::count();
        $this->info("Options: {$options}");
        
        $features = \App\Models\Feature::count();
        $this->info("Features: {$features}");
        
        $products = \App\Models\Product::count();
        $this->info("Products: {$products}");
        
        // Verificar productos con subcategorías
        $this->info("\nChecking product relationships...");
        
        $productsWithSubcategory = \App\Models\Product::with('subcategory.category.family')->get();
        foreach ($productsWithSubcategory as $product) {
            $subcategory = $product->subcategory;
            if ($subcategory) {
                $category = $subcategory->category;
                if ($category) {
                    $family = $category->family;
                    if ($family) {
                        $this->info("Product '{$product->name}' -> Subcategory '{$subcategory->name}' -> Category '{$category->name}' -> Family '{$family->name}' (ID: {$family->id})");
                    } else {
                        $this->info("Product '{$product->name}' -> Subcategory '{$subcategory->name}' -> Category '{$category->name}' -> NO FAMILY");
                    }
                } else {
                    $this->info("Product '{$product->name}' -> Subcategory '{$subcategory->name}' -> NO CATEGORY");
                }
            } else {
                $this->info("Product '{$product->name}' -> NO SUBCATEGORY");
            }
        }
        
        // Test family filter con productos específicos
        $this->info("\nTesting family filters...");
        
        // Probar con las familias que realmente tienen productos
        $familiesWithProducts = [17, 18];
        
        foreach ($familiesWithProducts as $familyId) {
            $this->info("\nTesting family ID: {$familyId}");
            
            $productsInFamily = \App\Models\Product::whereHas('subcategory.category', function ($query) use ($familyId) {
                $query->where('family_id', $familyId);
            })->get();
            
            $this->info("Products in family {$familyId}: {$productsInFamily->count()}");
            
            $optionsForFamily = \App\Models\Option::whereHas('products', function ($query) use ($familyId) {
                $query->whereHas('subcategory.category', function ($subQuery) use ($familyId) {
                    $subQuery->where('family_id', $familyId);
                });
            })->with('features')->get();
            
            $this->info("Options for family {$familyId}: {$optionsForFamily->count()}");
            
            foreach ($optionsForFamily as $option) {
                $this->info("  - Option '{$option->name}' with {$option->features->count()} features");
            }
        }
    }
}
