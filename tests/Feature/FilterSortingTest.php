<?php

namespace Tests\Feature;

use App\Livewire\Filter;
use App\Models\Family;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Option;
use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilterSortingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $family = Family::factory()->create(['name' => 'Test Family']);
        $category = Category::factory()->create(['family_id' => $family->id, 'name' => 'Test Category']);
        $subcategory = Subcategory::factory()->create(['category_id' => $category->id, 'name' => 'Test Subcategory']);
        
        // Create test products with different prices and names
        Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Product A',
            'price' => 100.00,
            'created_at' => now()->subDays(3)
        ]);
        
        Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Product B',
            'price' => 50.00,
            'created_at' => now()->subDays(1)
        ]);
        
        Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Product C',
            'price' => 200.00,
            'created_at' => now()->subDays(2)
        ]);
    }

    /** @test */
    public function it_can_sort_products_by_price_ascending()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->set('sortBy', 'price_asc')
            ->assertSeeInOrder(['Product B', 'Product A', 'Product C']);
    }

    /** @test */
    public function it_can_sort_products_by_price_descending()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->set('sortBy', 'price_desc')
            ->assertSeeInOrder(['Product C', 'Product A', 'Product B']);
    }

    /** @test */
    public function it_can_sort_products_by_name()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->set('sortBy', 'name_asc')
            ->assertSeeInOrder(['Product A', 'Product B', 'Product C']);
    }

    /** @test */
    public function it_can_sort_products_by_newest()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->set('sortBy', 'newest')
            ->assertSeeInOrder(['Product B', 'Product C', 'Product A']);
    }

    /** @test */
    public function it_can_clear_all_filters()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->set('sortBy', 'price_desc')
            ->set('selectedFeatures', [1, 2])
            ->call('clearFilters')
            ->assertSet('sortBy', 'relevance')
            ->assertSet('selectedFeatures', []);
    }

    /** @test */
    public function it_can_search_products_by_name()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->call('search', 'Product B')
            ->assertSet('search', 'Product B')
            ->assertCount('products', 1)
            ->assertSee('Product B')
            ->assertDontSee('Product A')
            ->assertDontSee('Product C');
    }

    /** @test */
    public function it_can_search_products_by_partial_name()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->call('search', 'Product')
            ->assertSet('search', 'Product')
            ->assertCount('products', 3)
            ->assertSee('Product A')
            ->assertSee('Product B')
            ->assertSee('Product C');
    }

    /** @test */
    public function it_can_clear_search_term()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->call('search', 'Product B')
            ->assertSet('search', 'Product B')
            ->assertCount('products', 1)
            ->set('search', '')
            ->assertSet('search', '')
            ->assertCount('products', 3);
    }

    /** @test */
    public function search_works_with_filters_and_sorting()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->call('search', 'Product')
            ->set('sortBy', 'price_desc')
            ->assertSet('search', 'Product')
            ->assertSet('sortBy', 'price_desc')
            ->assertSeeInOrder(['Product C', 'Product A', 'Product B']);
    }

    /** @test */
    public function clear_filters_also_clears_search()
    {
        $family = Family::first();
        
        Livewire::test(Filter::class, ['family_id' => $family->id])
            ->call('search', 'Product B')
            ->set('sortBy', 'price_desc')
            ->set('selectedFeatures', [1])
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('sortBy', 'relevance')
            ->assertSet('selectedFeatures', []);
    }
}
