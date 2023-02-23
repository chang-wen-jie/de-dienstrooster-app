<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Presentieoverzicht') }}
        </h2>
    </x-slot>

    <div id='calendar'></div>

    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                weekMode: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: {
                    url: '/events', // Fetch data vanuit database
                    error: function() {
                        alert('There was an error fetching events.');
                        console.log('Events:', events);
                    },
                },
            });
        });
    </script>

    <style>
        #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
    </style>
</x-app-layout>
