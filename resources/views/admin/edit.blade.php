<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $employee->name }}
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
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Personeelsgegevens aanpassen') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Wijzig hier de personeelsnaam en/of de activiteitsstatus.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('updateUser', $employee->id) }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="id" :value="__('Personeelsnummer')" />
                                <x-text-input id="id" name="id" type="text" class="mt-1 block w-full" :value="old('name', $employee->id)" disabled />
                            </div>

                            <div>
                                <x-input-label for="name" :value="__('Naam')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $employee->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div class="block mt-4">
                                <label for="active" class="inline-flex items-center">
                                    <input id="active" name="active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="active" value="1" {{ $employee->active ? 'checked="checked' : '' }}"/>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Is dit personeel nog actief?') }}</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Opslaan') }}</x-primary-button>
                            </div>
                            @method('PUT')
                        </form>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Dienst inroosteren') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Geef de datum en start- en eindtijd van de aankomende dienst op. Ingeroosterde diensten zijn zichtbaar binnen de kalender.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('setEvent', $employee->id) }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="shift-date" :value="__('Datum')" />
                                <input type="date" id="shift-date" name="shift-date" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
                                <x-input-error class="mt-2" :messages="$errors->get('shift-date')" />
                            </div>

                            <div class="flex gap-4">
                                <div>
                                    <x-input-label for="shift-start" :value="__('Starttijd')" />
                                    <input type="time" id="shift-start" name="shift-start" required>
                                    <x-input-error class="mt-2" :messages="$errors->get('shift-start')" />
                                </div>

                                <div>
                                    <x-input-label for="shift-end" :value="__('Eindtijd')" />
                                    <input type="time" id="shift-end" name="shift-end" required>
                                    <x-input-error class="mt-2" :messages="$errors->get('shift-end')" />
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Inroosteren') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Afwezigheid registreren') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Geef het datumbereik gepaard met de afwezigheidsreden op. Geregistreerde afwezigheden zijn zichtbaar binnen de kalender.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('setEvent', $employee->id) }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="absence-reason" :value="__('Reden')" />
                                <select id="absence-reason" name="absence-reason">
                                    <option value="sick">Ziek</option>
                                    <option value="leave">Vakantie</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="absence-start" :value="__('Startdatum')" />
                                <input type="date" id="absence-start" name="absence-start" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+1 year')) }}T23:59" required>
                                <x-input-error class="mt-2" :messages="$errors->get('absence-start')" />
                            </div>

                            <div>
                                  <x-input-label for="absence-end" :value="__('Einddatum')" />
                                <input type="date" id="absence-end" name="absence-end" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+1 year')) }}T23:59" required>
                                <x-input-error class="mt-2" :messages="$errors->get('absence-end')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Registreren') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Basisrooster opstellen') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __("Stel hier een basisrooster voor dit personeel op. Geef aan op welke dag(en) het personeel ingeroosterd zal zijn tijdens de even en oneven weken.") }}
                                </p>
                            </header>

                            <form method="post" action="{{ route('setEvent', $employee->id) }}" class="mt-6 space-y-6">
                                @csrf
                                <div>
                                    Even weken
                                    <x-input-label for="shift-start-mon" :value="__('Maandag')" />
                                    <div class="flex gap-4">
                                        <div>
                                            <x-input-label for="shift-start-mon" :value="__('Starttijd')" />
                                            <input type="time" id="shift-start-mon" name="shift-start-mon">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-start-mon')" />
                                        </div>

                                        <div>
                                            <x-input-label for="shift-end-mon" :value="__('Eindtijd')" />
                                            <input type="time" id="shift-end-mon" name="shift-end-mon">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-end-mon')" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="shift-start-tue" :value="__('Dinsdag')" />
                                    <div class="flex gap-4">
                                        <div>
                                            <x-input-label for="shift-start-tue" :value="__('Starttijd')" />
                                            <input type="time" id="shift-start-tue" name="shift-start-tue">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-start-tue')" />
                                        </div>

                                        <div>
                                            <x-input-label for="shift-end-tue" :value="__('Eindtijd')" />
                                            <input type="time" id="shift-end-tue" name="shift-end-tue">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-end-tue')" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="shift-start-wed" :value="__('Woensdag')" />
                                    <div class="flex gap-4">
                                        <div>
                                            <x-input-label for="shift-start-wed" :value="__('Starttijd')" />
                                            <input type="time" id="shift-start-wed" name="shift-start-wed">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-start-wed')" />
                                        </div>

                                        <div>
                                            <x-input-label for="shift-end-wed" :value="__('Eindtijd')" />
                                            <input type="time" id="shift-end-wed" name="shift-end-wed">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-end-wed')" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="shift-start-thu" :value="__('Donderdag')" />
                                    <div class="flex gap-4">
                                        <div>
                                            <x-input-label for="shift-start-thu" :value="__('Starttijd')" />
                                            <input type="time" id="shift-start-thu" name="shift-start-thu">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-start-thu')" />
                                        </div>

                                        <div>
                                            <x-input-label for="shift-end-thu" :value="__('Eindtijd')" />
                                            <input type="time" id="shift-end-thu" name="shift-end-thu">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-end-thu')" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="shift-start-fri" :value="__('Vrijdag')" />
                                    <div class="flex gap-4">
                                        <div>
                                            <x-input-label for="shift-start-fri" :value="__('Starttijd')" />
                                            <input type="time" id="shift-start-fri" name="shift-start-fri">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-start-fri')" />
                                        </div>

                                        <div>
                                            <x-input-label for="shift-end-fri" :value="__('Eindtijd')" />
                                            <input type="time" id="shift-end-fri" name="shift-end-fri">
                                            <x-input-error class="mt-2" :messages="$errors->get('shift-end-fri')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Opstellen') }}</x-primary-button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
