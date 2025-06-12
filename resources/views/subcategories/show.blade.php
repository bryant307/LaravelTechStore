@extends('layouts.app')

@section('content')
    <div class="py-8">
        <x-container>
            <div class="flex items-center mb-6">
                <a href="{{ route('families.show', $subcategory->category->family_id) }}" class="text-blue-600 hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
                <h1 class="text-3xl font-semibold ml-4">{{ $subcategory->name }}</h1>
            </div>

            <div class="mb-4 text-gray-600">
                <span>{{ $subcategory->category->family->name }} / {{ $subcategory->category->name }} / {{ $subcategory->name }}</span>
            </div>

            @php
                $options = \App\Models\Option::byFamily($subcategory->category->family_id)->with('features')->get();
                if ($options->isEmpty()) {
                    $options = \App\Models\Option::with('features')->get();
                }
            @endphp

            @livewire('filter', [
                'family_id' => $subcategory->category->family_id,
                'category_id' => $subcategory->category_id,
                'subcategory_id' => $subcategory->id,
                'options' => $options
            ])
        </x-container>
    </div>
@endsection
