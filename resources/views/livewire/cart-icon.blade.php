<div>
    <a href="{{ route('cart.index') }}" class="relative inline-block">
        <i class="fas fa-shopping-cart text-white text-xl md:text-3xl"></i>
        @if($count > 0)
            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ $count }}
            </span>
        @endif
    </a>
</div>
