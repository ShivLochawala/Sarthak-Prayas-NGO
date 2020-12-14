<x-app-layout>
    @section('content')
        <x-jet-authentication-card>

            <x-jet-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register-helper') }}">
                @csrf

                <div>
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <x-jet-label for="email" value="{{ __('Email') }}" />
                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <div class="mt-4">
                    <x-jet-label for="password" value="{{ __('Password') }}" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-jet-button class="ml-4">
                        {{ __('Add') }}
                    </x-jet-button>
                </div>
            </form>
        </x-jet-authentication-card>

    @endsection
</x-app-layout>