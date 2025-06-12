<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endpush
    <!-- Slider main container -->
    <div class="swiper mb-12">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            @foreach ($covers as $cover)
                <div class="swiper-slide">
                    <img src="{{ $cover->image }}" class="w-full aspect-[3/1] object-cover object-center" alt="">
                </div>
            @endforeach

        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <!-- If we need scrollbar -->
        <div class="swiper-scrollbar"></div>
    </div>

    <x-container>
        <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">
            Últimos productos
        </h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($lastProducts as $product)
                <article
                    class="bg-white shadow-md rounded-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="relative overflow-hidden">
                        <img src="{{ $product->image }}"
                            class="w-full h-52 object-cover object-center transition-transform duration-500 group-hover:scale-110"
                            alt="{{ $product->name }}">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-0 w-full p-4">
                                <span
                                    class="inline-block px-2 py-1 bg-blue-600 text-white text-xs font-semibold rounded">
                                    ${{ number_format($product->price ?? 0, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <h1
                            class="text-lg font-bold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $product->name }}
                        </h1>
                        <p class="text-gray-600 mb-4 text-sm">
                            {{ Str::limit($product->description ?? 'Sin descripción', 60) }}
                        </p>
                        <a href="{{ route('products.show', $product) }}"
                            class="btn btn-blue block w-full text-center transform transition-transform duration-300 group-hover:scale-105">
                            <i class="fas fa-eye mr-2"></i>
                            Ver producto
                        </a>
                    </div>
                </article>
            @endforeach

        </div>

        {{-- Seccion de Productos Destacados --}}
        <section class="mt-16 mb-12">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <a href="{{ route('families.show', ['family' => 1]) }}"
                    class="block group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('img/home/DPadre.JPG') }}"
                        class="w-full h-64 object-cover object-center transition-transform duration-500 group-hover:scale-110"
                        alt="Laptops">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-4 text-white">
                            <h3 class="font-bold text-xl mb-1">Laptops</h3>
                            <p class="text-sm text-white/80">Potencia y portabilidad</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('families.show', ['family' => 2]) }}"
                    class="block group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('img/home/image.png') }}"
                        class="w-full h-64 object-cover object-center transition-transform duration-500 group-hover:scale-110"
                        alt="Smartphones">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-4 text-white">
                            <h3 class="font-bold text-xl mb-1">Smartphones</h3>
                            <p class="text-sm text-white/80">Lo último en tecnología móvil</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('families.show', ['family' => 3]) }}"
                    class="block group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('img/home/samsung evo.webp') }}"
                        class="w-full h-64 object-cover object-center transition-transform duration-500 group-hover:scale-110"
                        alt="Accesorios">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-4 text-white">
                            <h3 class="font-bold text-xl mb-1">Accesorios</h3>
                            <p class="text-sm text-white/80">Complementos esenciales</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('families.show', ['family' => 4]) }}"
                    class="block group relative overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('img/home/Control para Juegos XTRIKE ME Wireless Bluetooth GP-43  compatible con PS4_PS3_PC_ANDROID_IOS.jpg') }}"
                        class="w-full h-64 object-cover object-center transition-transform duration-500 group-hover:scale-110"
                        alt="Gaming">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                        <div class="p-4 text-white">
                            <h3 class="font-bold text-xl mb-1">Gaming</h3>
                            <p class="text-sm text-white/80">Equipo para gamers</p>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <!-- Sección de Familias con Productos -->
        @if (isset($familiesWithProducts) && $familiesWithProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Explorar por Categorías
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($familiesWithProducts as $family)
                        <a href="{{ route('families.show', $family) }}"
                            class="bg-white shadow rounded-lg p-4 hover:shadow-lg transition-shadow border-l-4 border-blue-500">
                            <h3 class="font-semibold text-gray-800 mb-2">{{ $family->name }}</h3>
                            <p class="text-gray-600 text-sm">
                                Ver productos y filtros disponibles
                            </p>
                            <div class="mt-2 text-blue-600 text-sm font-medium">
                                Explorar →
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-[20px]">
                <div class="w-250 h-100">
                    <img src="{{ asset('img/home/D.JPG') }}"
                        class="w-full h-64 object-cover object-center transition-transform duration-500 group-hover:scale-110"
                        alt="Laptops">
                </div>
            </div>
        @endif

        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 mt-12 items-center text-center">
                Opiniones de nuestros clientes
            </h2>
        </div>
        <div
            class="grid mb-8 border border-gray-200 rounded-lg shadow-xs dark:border-gray-700 md:mb-12 md:grid-cols-2 bg-white dark:bg-gray-800">
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 rounded-t-lg md:rounded-t-none md:rounded-ss-lg md:border-e dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Envio rapido</h3>
                    <p class="my-4">Hice la compra y en menos de 48 horas ya habia recibido mi pedido"</p>
                </blockquote>
                <figcaption class="flex items-center justify-center ">
                    <img class="rounded-full w-9 h-9"
                        src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/karen-nelson.png"
                        alt="profile picture">
                    <div class="space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3">
                        <div>Claudia Alvarez</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 "></div>
                    </div>
                </figcaption>
            </figure>
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 md:rounded-se-lg dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Simple y efectivo</h3>
                    </h3>
                    <p class="my-4">El proceso de compra es muy sencillo y rapido, me encanto la experiencia."</p>
                </blockquote>
                <figcaption class="flex items-center justify-center ">
                    <img class="rounded-full w-9 h-9"
                        src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/roberta-casas.png"
                        alt="profile picture">
                    <div class="space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3">
                        <div>Nadia Coreas</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400"></div>
                    </div>
                </figcaption>
            </figure>
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-b border-gray-200 md:rounded-es-lg md:border-b-0 md:border-e dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Devolucion garantizada </h3>
                    <p class="my-4">Me llego un producto defectuoso y me lo cambiaron sin problemas, excelente
                        servicio."</p>
                </blockquote>
                <figcaption class="flex items-center justify-center ">
                    <img class="rounded-full w-9 h-9"
                        src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png"
                        alt="profile picture">
                    <div class="space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3">
                        <div>Jose Leon</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400"></div>
                    </div>
                </figcaption>
            </figure>
            <figure
                class="flex flex-col items-center justify-center p-8 text-center bg-white border-gray-200 rounded-b-lg md:rounded-se-lg dark:bg-gray-800 dark:border-gray-700">
                <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Facil de compra</h3>
                    <p class="my-4">Me parecio una pagina muy intuitiva y facil de realizar compras."</p>
                </blockquote>
                <figcaption class="flex items-center justify-center ">
                    <img class="rounded-full w-9 h-9"
                        src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/joseph-mcfall.png"
                        alt="profile picture">
                    <div class="space-y-0.5 font-medium dark:text-white text-left rtl:text-right ms-3">
                        <div>Will Salgado</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400"></div>
                    </div>
                </figcaption>
            </figure>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 mt-12 items-center text-center">
                Suscribete a nuestro boletin
            </h2>
            <p class="text-gray-600 mb-6 text-center">
                Recibe las últimas novedades y ofertas exclusivas directamente en tu bandeja de entrada.


            <div>
                <label for="helper-text" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tu
                    email</label>
                <input type="email" id="helper-text" aria-describedby="helper-text-explanation"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="name@flowbite.com">
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">We’ll never
                    share
                    your
                    details. Read our <a href="#"
                        class="font-medium text-blue-600 hover:underline dark:text-blue-500">Privacy Policy</a>.</p>
                <button>
                    <div class="mt-4">
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors">
                            Suscribirse
                        </button>
                    </div>
            </div>

    </x-container>




    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


        <script>
            const swiper = new Swiper('.swiper', {
                // Optional parameters
                loop: true,

                autoplay: {
                    delay: 9000,
                },

                // If we need pagination
                pagination: {
                    el: '.swiper-pagination',
                },

                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                // And if we need scrollbar
                scrollbar: {
                    el: '.swiper-scrollbar',
                },
            });
        </script>
    @endpush
</x-app-layout>
