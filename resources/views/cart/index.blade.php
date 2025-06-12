@extends('layouts.app')

@section('content')
<div class="py-8">    <x-container>
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                {{ session('error') }}
            </div>
        @endif
        
        @livewire('cart-detail')
    </x-container>
</div>
@endsection
