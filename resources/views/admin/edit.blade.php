<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Personeelsgegevens aanpassen') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Wijzig hier de gegevens van een geselecteerd personeel") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('users.update', $user->id) }}" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="id" :value="__('Personeelsnummer')" />
                                <x-text-input id="id" name="id" type="text" class="mt-1 block w-full" :value="old('name', $user->id)" disabled />
                            </div>

                            <div>
                                <x-input-label for="name" :value="__('Naam')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div class="block mt-4">
                                <label for="active" class="inline-flex items-center">
                                    <input id="active" name="active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="active" value="1" {{ $user->active ? 'checked="checked' : '' }}"/>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Actief?') }}</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Opslaan') }}</x-primary-button>
                                <a href="{{ route('users.admin') }}">
                                    {{ __('Annuleren') }}
                                </a>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
