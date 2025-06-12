<div class="product-gallery">
    <!-- Imagen principal -->
    <div class="relative mb-4">
        <img 
            src="{{ $currentImage }}" 
            alt="{{ $product->name }}"
            class="w-full h-96 object-contain rounded-lg"
            id="main-product-image"
        >
        
        <!-- Botones de compartir en redes sociales -->
        <div class="absolute bottom-4 right-4 bg-white bg-opacity-80 rounded-lg p-2 shadow-sm flex gap-3 social-share">
            <!-- Facebook -->
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" 
               target="_blank" rel="noopener noreferrer"
               class="text-blue-600 hover:text-blue-800 transition-colors">
                <i class="fab fa-facebook-f text-xl"></i>
            </a>
            
            <!-- Twitter/X -->
            <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode('¡Mira este increíble producto: ' . $product->name) }}" 
               target="_blank" rel="noopener noreferrer"
               class="text-blue-500 hover:text-blue-700 transition-colors">
                <i class="fab fa-twitter text-xl"></i>
            </a>
            
            <!-- WhatsApp -->
            <a href="https://wa.me/?text={{ urlencode('¡Mira este increíble producto: ' . $product->name . ' ' . $shareUrl) }}" 
               target="_blank" rel="noopener noreferrer"
               class="text-green-600 hover:text-green-800 transition-colors">
                <i class="fab fa-whatsapp text-xl"></i>
            </a>
            
            <!-- Pinterest -->
            <a href="https://pinterest.com/pin/create/button/?url={{ urlencode($shareUrl) }}&media={{ urlencode($currentImage) }}&description={{ urlencode($product->name . ' - ' . $product->description) }}" 
               target="_blank" rel="noopener noreferrer"
               class="text-red-600 hover:text-red-800 transition-colors">
                <i class="fab fa-pinterest-p text-xl"></i>
            </a>
            
            <!-- Correo electrónico -->
            <a href="mailto:?subject={{ urlencode('¡Mira este producto en LaravelTechStore!') }}&body={{ urlencode('Hola, creo que te podría interesar este producto: ' . $product->name . '. Visítalo aquí: ' . $shareUrl) }}" 
               class="text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-envelope text-xl"></i>
            </a>
            
            <!-- Copiar enlace -->
            <button 
                onclick="navigator.clipboard.writeText('{{ $shareUrl }}'); this.classList.add('copied'); setTimeout(() => this.classList.remove('copied'), 2000);"
                class="text-gray-600 hover:text-gray-800 transition-colors copy-link relative"
                title="Copiar enlace">
                <i class="fas fa-link text-xl"></i>
                <span class="copy-tooltip absolute bg-gray-900 text-white text-xs py-1 px-2 rounded -bottom-8 left-1/2 transform -translate-x-1/2 opacity-0">
                    Copiado
                </span>
            </button>
        </div>
    </div>
    
    <!-- Miniaturas -->
    @if(count($thumbnails) > 1)
        <div class="grid grid-cols-5 gap-2">
            @foreach($thumbnails as $thumbnail)
                <button 
                    wire:click="selectImage('{{ $thumbnail['id'] }}')"
                    class="border rounded-lg overflow-hidden {{ $thumbnail['is_current'] ? 'border-blue-500 ring-2 ring-blue-300' : 'border-gray-300 hover:border-gray-400' }}">
                    <img 
                        src="{{ $thumbnail['image'] }}" 
                        alt="{{ $product->name }}"
                        class="w-full h-20 object-contain"
                    >
                </button>
            @endforeach
        </div>
    @endif
    
    <style>
        .social-share a, .social-share button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        .social-share a:hover, .social-share button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .copy-link.copied .copy-tooltip {
            opacity: 1;
            transition: opacity 0.3s;
        }
    </style>
    
    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('productImageChanged', (image) => {
                // Se podría agregar animación de transición aquí
                console.log('Imagen cambiada a:', image);
            });
            
            // Eventos de analytics (opcional)
            document.querySelectorAll('.social-share a').forEach(link => {
                link.addEventListener('click', function() {
                    console.log('Compartido en:', this.getAttribute('href').split('//')[1].split('.')[0]);
                    // Aquí se podría agregar código para registrar el evento en analytics
                });
            });
        });
    </script>
    @endpush
</div>
