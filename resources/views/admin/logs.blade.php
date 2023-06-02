<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $employee->name }}{{ __("'s Logboek") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Activiteitengeschiedenis') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Alle activiteiten van het personeel gedateerd tot de creatie van personeelsaccount.') }}
                            </p>
                        </header>

                        @foreach($employee->logging()->orderBy('updated_at', 'desc')->get() as $log)
                            @if($log->presence_state === 'CHECKED IN')
                                <div class="mt-6 space-y-6">
                                    <b>Ingecheckt</b> om <b>{{ $log->updated_at }}</b>
                                    @if ($log->session_time > 60)
                                        (Afwezig voor {{ intdiv($log->session_time, 60) }} uur en {{ $log->session_time % 60 }} minuten)
                                    @else
                                        (Afwezig voor {{ $log->session_time }} minuten)
                                    @endif
                                </div>
                            @else
                                <div class="mt-6 space-y-6">
                                    <b>Uitgecheckt</b> om <b>{{ $log->updated_at }}</b>
                                    @if ($log->session_time > 60)
                                        (Aanwezig voor {{ intdiv($log->session_time, 60) }} uur en {{ $log->session_time % 60 }} minuten)
                                    @else
                                        (Aanwezig voor {{ $log->session_time }} minuten)
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
