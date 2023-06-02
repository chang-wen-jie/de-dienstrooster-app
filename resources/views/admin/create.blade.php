<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Personeel Toevoegen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Personeel toevoegen') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Voer hier een nieuw personeel toe binnen dit systeem.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('store') }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="rfid" :value="__('RFID')" />
                                <x-text-input id="rfid" name="rfid" type="text" class="mt-1 block w-full" required autofocus autocomplete="rfid" />
                                <x-input-error class="mt-2" :messages="$errors->get('rfid')" />
                            </div>

                            <div>
                                <x-input-label for="name" :value="__('Naam')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('E-mailadres')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required autofocus autocomplete="email" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Wachtwoord')" />
                                <x-text-input id="password" password="password" type="text" class="mt-1 block w-full" required autofocus autocomplete="password" />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Toevoegen') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
