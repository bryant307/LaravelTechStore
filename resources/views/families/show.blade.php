@extends('layouts.app')

@section('content')
    <div class="py-8">
        <x-container>
            <h1 class="text-3xl font-semibold mb-6">{{ $family->name }}</h1>
            
            @livewire('filter', ['family_id' => $family->id, 'options' => $options])
        </x-container>
    </div>
@endsection