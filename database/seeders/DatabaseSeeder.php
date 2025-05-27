<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Seeder;
use Database\Seeders\FamilySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Storage::deleteDirectory('products');
        //User::factory(10)->create();
//
        User::factory()->create([
            'name' => 'Bryan Torres',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);

        $this->call([
            FamilySeeder::class,
        ]);

        Product::factory(200)->create();
    }


}
