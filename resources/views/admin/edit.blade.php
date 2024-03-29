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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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

                        <form method="post" action="{{ route('update', $employee->id) }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="id" :value="__('Personeelsnummer')" />
                                <x-text-input id="id" name="id" type="text" class="mt-1 block w-full" :value="old('name', $employee->id)" disabled />
                            </div>

                            <div>
                                <x-input-label for="rfid" :value="__('RFID')" />
                                <x-text-input id="rfid" name="rfid" type="text" class="mt-1 block w-full" :value="old('rfid', $employee->rfid)" required autofocus autocomplete="rfid" />
                                <x-input-error class="mt-2" :messages="$errors->get('rfid')" />
                            </div>

                            <div>
                                <x-input-label for="name" :value="__('Naam')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $employee->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="account_type" :value="__('Rol')" />
                                <select id="account_type" name="account_type">
                                    <option value="admin" {{ $employee->account_type === 'admin' ? 'selected' : '' }}>Beheerder</option>
                                    <option value="user" {{ $employee->account_type === 'user' ? 'selected' : '' }}>Medewerker</option>
                                </select>
                            </div>

                            <div class="block mt-4">
                                <label for="account_status" class="inline-flex items-center">
                                    <input id="account_status" name="account_status" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="active" value="1" {{ $employee->active ? 'checked="checked' : '' }}"/>
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Actief') }}</span>
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
                                <input type="date" id="shift-date" name="shift-date" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('last day of december this year')) }}" required>
                                <x-input-error class="mt-2" :messages="$errors->get('shift-date')" />
                            </div>

                            <div class="flex gap-4">
                                <div>
                                    <x-input-label for="shift-start-time" :value="__('Starttijd')" />
                                    <input type="time" id="shift-start-time" name="shift-start-time" required>
                                    <x-input-error class="mt-2" :messages="$errors->get('shift-start-time')" />
                                </div>

                                <div>
                                    <x-input-label for="shift-end-time" :value="__('Eindtijd')" />
                                    <input type="time" id="shift-end-time" name="shift-end-time" required>
                                    <x-input-error class="mt-2" :messages="$errors->get('shift-end-time')" />
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
                                {{ __("Geef aan tijdens welke datumbereik de afwezigheid zal plaatsvinden gepaard met de afwezigheidsreden. Geregistreerde afwezigheden zijn zichtbaar binnen de kalender.") }}
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
                                <x-input-label for="absence-date-start" :value="__('Startdatum')" />
                                <input type="date" id="absence-date-start" name="absence-date-start" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('last day of december this year')) }}" required>
                                <x-input-error class="mt-2" :messages="$errors->get('absence-date-start')" />
                            </div>

                            <div>
                                <x-input-label for="absence-date-end" :value="__('Einddatum')" />
                                <input type="date" id="absence-date-end" name="absence-date-end" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('last day of december this year')) }}" required>
                                <x-input-error class="mt-2" :messages="$errors->get('absence-date-end')" />
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
                                {{ __("Stel hier een basisrooster voor dit personeel op. Het basisrooster herhaalt zich om de vijf weken en de eerste instantie van de rotatie zal ingaan vanaf de seselecteerde startdatum.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('setSchedule', $employee->id) }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="scheduled-week" :value="__('Weeknummer')" />
                                <select id="scheduled-week" name="scheduled-week" onchange="fetchSchedule({{ $employee->id }}, this)">
                                    <option value="" selected>
                                        Kies een week
                                    </option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div id="fetched_week" data-changed="0"></div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Opstellen') }}</x-primary-button>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button name="schedule-next-year">{{ __('Inroosteren voor volgend jaar') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
