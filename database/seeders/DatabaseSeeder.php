<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpiar directorio de imágenes de productos
        Storage::deleteDirectory('products');
        Storage::makeDirectory('products');

        // Actualizar usuario Bryan como administrador
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Bryan',
                'last_name' => 'Torres',
                'document_type' => 'DUI',
                'document_number' => '058905212',
                'email' => 'admin@admin.com',
                'phone' => '78787878',
                'password' => bcrypt('12345678'),
                'is_admin' => true, // Asignar privilegios de administrador
            ]
        );

        // Crear familias de productos
        $families = [
            'Electrónicos',
            'Informática',
            'Hogar',
            'Deportes'
        ];

        foreach ($families as $familyName) {
            $family = Family::create(['name' => $familyName]);
            
            // Crear categorías para cada familia
            for ($i = 1; $i <= 2; $i++) {
                $category = Category::create([
                    'name' => $familyName . ' - Categoría ' . $i,
                    'family_id' => $family->id
                ]);
                
                // Crear subcategorías para cada categoría
                for ($j = 1; $j <= 2; $j++) {
                    Subcategory::create([
                        'name' => $category->name . ' - Subcategoría ' . $j,
                        'category_id' => $category->id
                    ]);
                }
            }
        }

        // Crear productos
        Product::factory(20)->create();

        // Ejecutamos solo los seeders que existen
        $this->call([
            AdminUserSeeder::class, // Incluir nuestro seeder de administrador
        ]);

        Product::factory(200)->create();
    }


}
