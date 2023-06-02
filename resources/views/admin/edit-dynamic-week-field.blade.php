@foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $weekday)
    @php
        $employee_scheduled_shift = $employee->schedule()->where('weekday', $weekday)->where('week', $week)->first();
    @endphp
    <div>
        <input type="hidden" name="weekdays[]" value="{{ $weekday }}">
        <x-input-label for="shift-start-time-{{ $weekday }}" :value="__(ucfirst($weekday))" />
        <div class="flex gap-4">
            <div>
                <x-input-label for="shift-start-time-{{ $weekday }}" :value="__('Starttijd')" />
                <input type="time" id="shift-start-time-{{ $weekday }}" name="shift-start-time-{{ $weekday }}" value="{{ $employee_scheduled_shift ? date('H:i', strtotime($employee_scheduled_shift->shift_start_time)) : '' }}">
                <x-input-error class="mt-2" :messages="$errors->get('shift-start-time-' . $weekday)" />
            </div>

            <div>
                <x-input-label for="shift-end-time-{{ $weekday }}" :value="__('Eindtijd')" />
                <input type="time" id="shift-end-time-{{ $weekday }}" name="shift-end-time-{{ $weekday }}" value="{{ $employee_scheduled_shift ? date('H:i', strtotime($employee_scheduled_shift->shift_end_time)) : '' }}">
                <x-input-error class="mt-2" :messages="$errors->get('shift-end-time-' . $weekday)" />
            </div>
        </div>
    </div>
@endforeach

<div>
    <div>
        <x-input-label for="schedule-start-date" :value="__('Startdatum')" />
        <input type="date" id="schedule-start-date" name="schedule-start-date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('last day of december this year')) }}">
        <x-input-error class="mt-2" :messages="$errors->get('schedule-start-date')" />
    </div>

    <div>
        <x-input-label for="schedule-end-date" :value="__('Einddatum')" />
        <input type="date" id="schedule-end-date" name="schedule-end-date" value="{{ date('Y-m-d', strtotime('last day of december this year')) }}" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('last day of december this year')) }}">
        <x-input-error class="mt-2" :messages="$errors->get('schedule-end-date')" />
    </div>
</div>
