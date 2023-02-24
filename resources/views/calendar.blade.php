<x-app-layout>
    <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight float:left">
                {{ __('Presentieoverzicht') }}
            </h2>

        <div class="text-center float:right">
            <span class="bubble bg-info"></span> Op kantoor
            <span class="bubble bg-success"></span> Werkt vanuit thuis
            <span class="bubble bg-warning"></span> Afwezig
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">
                    <div class="min-w-full align-middle">
                        <div style="text-align: center">
                        </div>

                        <div id='calendar'></div>

                        <script>
                            $(document).ready(function() {
                                $('#calendar').fullCalendar({
                                    displayEventTime: false,
                                    header: {
                                        left: 'title',
                                        center: '',
                                        right: 'prev,next today'
                                    },

                                    // Haalt asynchronisch de JSON-geformatteerde databasegegevens vanuit de URL op.
                                    events: function(start, end, timezone, callback) {
                                        $.ajax({
                                            url: '/events',
                                            dataType: 'json',
                                            data: {
                                                start: start.format(),
                                                end: end.format(),
                                            },

                                            success: function(presence) {
                                                const events = [];
                                                for (let i = 0; i < presence.length; i++) {
                                                    const event = presence[i];
                                                    events.push({
                                                        title: event.title,
                                                        start: event.start,
                                                        employed: event.employed,
                                                        in_office: event.in_office,
                                                        color: event.employed
                                                            ? event.in_office
                                                                ? '#3aad60'
                                                                : '#3a87ad'
                                                            : '#6f7578',
                                                        textColor: 'white'
                                                    });
                                                }
                                                callback(events);
                                            }
                                        });
                                    },
                                });
                            });
                        </script>

                        <style>
                            .bubble {
                                height: 11px;
                                width: 25px;
                                background-color: #3aad60;
                                border-radius: 3px;
                                display: inline-block;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
