<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">


                <div>
                    <x-label for="last_name" value="{{ __('Name') }}" />
                    <x-input id="last_name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                        required autofocus autocomplete="name" />
                </div>
                <div>
                    <x-label for="name" value="Apellidos" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')"
                        required autocomplete="last_name" />
                </div>

                {{-- Tipo de documento --}}

                <div>
                    <x-label for="document_type" value="Tipo de documento" />
                    <x-select class="w-full" id="document_type" name="document_type" >
                    @foreach (\App\Enums\TypeOfDocuments::cases() as $item)
                        <option value="{{ $item->value }}">
                            {{ $item->name }}
                        </option>     
                        
                    @endforeach
                        {{-- <option value="1">DUI</option>
                        <option value="2">Pasaporte</option> --}}
                    </x-select>
                </div>

                <div>
                    <x-label for="document_number" value="Número de documento" />
                    <x-input id="document_number" class="block mt-1 w-full" type="text"
                        name="document_number" :value="old('document_number')" required
                        autocomplete="document_number" />
                </div>
                <div class="mt-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        required autocomplete="username" />
                </div>

                {{-- Telefono --}}
                <div class="mt-4">
                    <x-label for="phone" value="Teléfono" />
                    <x-input id="phone" class="block mt-1 w-full" type="text" name="phone"
                        :value="old('phone')" required autocomplete="phone" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' =>
                                        '<a target="_blank" href="' .
                                        route('terms.show') .
                                        '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                        __('Terms of Service') .
                                        '</a>',
                                    'privacy_policy' =>
                                        '<a target="_blank" href="' .
                                        route('policy.show') .
                                        '" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">' .
                                        __('Privacy Policy') .
                                        '</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
