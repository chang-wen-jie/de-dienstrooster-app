<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Presentieoverzicht') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">
                    <div class="min-w-full align-middle">

                        <div class="text-center">
                            <span class="color-bubble bg-success"></span> Aanwezig
                            <span class="color-bubble bg-warning"></span> Vrij
                            <span class="color-bubble bg-danger"></span> Ziek

                            <select id="filter">
                                <option value="">Alles</option>
                                <option value="onDuty">Aanwezig</option>
                                <option value="sick">Ziek</option>
                            </select>

                        </div>

                        <div id='calendar'></div>

                        <script>
                            $(document).ready(function() {
                                // FUllCalendar API
                                $('#calendar').fullCalendar({
                                    eventRender: function(event, element) {
                                        var shiftStart = moment(event.start);
                                        var shiftEnd = moment(event.shiftEnd);

                                        if (shiftStart.hour() >= 12) {
                                            element.css({
                                                'width': '50%',
                                                'float': 'right',
                                            });
                                        } else if (shiftEnd.hour() <= 12) {
                                            element.css({
                                                'width': '50%',
                                            });
                                        }
                                    },

                                    // Kalender opmaakopties
                                    displayEventTime: false,
                                    header: {
                                        left: 'title',
                                        center: '',
                                        right: 'prev,next today'
                                    },

                                    // Haal databasegegevens vanuit op
                                    events: function(start, end, timezone, callback) {
                                        const filter = $('#filter').val();

                                        $.ajax({
                                            url: '/events',
                                            dataType: 'json',
                                            data: {
                                                start: start.format(),
                                                end: end.format(),
                                                filter: filter,
                                            },

                                            success: function(presence) {
                                                const events = [];
                                                for (let i = 0; i < presence.length; i++) {
                                                    const event = presence[i];
                                                    if (!filter || event[filter]) {
                                                        events.push({
                                                            title: event.name,
                                                            onDuty: event.onDuty,
                                                            start: event.start,
                                                            shiftEnd: event.shiftEnd,
                                                            sick: event.sick,
                                                            color: event.sick ? '#dc3545' : event.onDuty ? '#28a745' : '#ffc107',
                                                            textColor: 'white'
                                                        });
                                                    }
                                                }
                                                callback(events);
                                            }
                                        });
                                    },
                                });
                            });

                            // Filter kalender overzicht
                            $('#filter').on('change', function() {
                                calendar.fullCalendar('refetchEvents');
                            });
                        </script>

                        <style>
                            .color-bubble {
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
