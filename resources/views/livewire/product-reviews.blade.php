<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-semibold mb-4">Opiniones de los compradores</h2>
    
    <!-- Resumen de valoraciones -->
    <div class="flex items-center gap-4 mb-8">
        <div>
            <div class="flex items-center">
                <span class="text-3xl font-bold">{{ number_format($averageRating, 1) }}</span>
                <span class="text-lg text-gray-600 ml-1">/ 5</span>
            </div>
            <div class="flex mt-1">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                @endfor
            </div>
            <div class="text-sm text-gray-600 mt-1">
                {{ $totalReviews }} {{ $totalReviews === 1 ? 'valoración' : 'valoraciones' }}
            </div>
        </div>
        
        <!-- Botón para escribir reseña -->
        <div class="ml-auto">
            <button
                wire:click="toggleReviewForm"
                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded transition-colors">
                Escribir una reseña
            </button>
        </div>
    </div>
    
    <!-- Formulario para escribir reseña (oculto por defecto) -->
    @if($showReviewForm)
        <div class="bg-gray-50 p-6 rounded-lg mb-8 border border-gray-200">
            <h3 class="text-lg font-semibold mb-4">Tu opinión sobre este producto</h3>
            
            <form wire:submit.prevent="submitReview">
                <!-- Mensajes flash -->
                @if(session()->has('error'))
                    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session()->has('success'))
                    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- Valoración con estrellas -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valoración:</label>
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    wire:click="$set('rating', {{ $i }})"
                                    class="text-2xl focus:outline-none {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                <i class="fas fa-star"></i>
                            </button>
                        @endfor
                    </div>
                    @error('rating') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Título de la reseña -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título (opcional):</label>
                    <input type="text" 
                           id="title" 
                           wire:model="title" 
                           class="w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Comentario -->
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Comentario:</label>
                    <textarea id="comment" 
                              wire:model="comment" 
                              rows="4" 
                              class="w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Comparte tu experiencia con este producto"></textarea>
                    @error('comment') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" 
                            wire:click="toggleReviewForm"
                            class="bg-gray-200 text-gray-800 py-2 px-6 rounded hover:bg-gray-300 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 text-white py-2 px-6 rounded hover:bg-blue-700 transition-colors">
                        Publicar reseña
                    </button>
                </div>
            </form>
        </div>
    @endif
    
    <!-- Lista de reseñas -->
    @if($reviews->count() > 0)
        <div class="space-y-8">
            @foreach($reviews as $review)
                <div class="border-b border-gray-200 pb-6 {{ !$loop->last ? 'mb-6' : '' }}">
                    <div class="flex justify-between mb-2">
                        <div class="flex items-center">
                            <!-- Estrellas de la valoración -->
                            <div class="flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            
                            <!-- Título de la reseña (si existe) -->
                            @if($review->title)
                                <h4 class="font-semibold ml-3">{{ $review->title }}</h4>
                            @endif
                        </div>
                        
                        <!-- Fecha de la reseña -->
                        <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <!-- Nombre del autor -->
                    <p class="text-sm text-gray-600 mb-2">
                        {{ $review->user->name }}
                        @if($review->verified_purchase)
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded ml-2">
                                <i class="fas fa-check-circle mr-1"></i> Compra verificada
                            </span>
                        @endif
                    </p>
                    
                    <!-- Contenido de la reseña -->
                    <p class="text-gray-800">{{ $review->comment }}</p>
                    
                    <!-- Opciones para el propietario de la reseña o administradores -->                        @auth
                        @if(auth()->id() == $review->user_id || auth()->user()->isAdmin())
                            <div class="mt-3 flex justify-end">
                                <button wire:click="deleteReview({{ $review->id }})" 
                                        class="text-red-600 text-sm hover:underline"
                                        wire:confirm="¿Estás seguro de que deseas eliminar esta reseña?">
                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        @if($reviews->hasPages())
            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-comments text-4xl mb-3"></i>
            <p>Este producto aún no tiene reseñas</p>
            <p class="text-sm mt-1">¡Sé el primero en compartir tu opinión!</p>
        </div>
    @endif
</div>
