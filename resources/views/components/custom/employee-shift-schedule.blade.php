@foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day_of_week)
    @php
        $employee_scheduled_shift = $employee->schedule()->where('day_of_week', $day_of_week)->where('scheduled_week', $scheduled_week)->first();
    @endphp
    <div>
        <input type="hidden" name="days-of-week[]" value="{{ $day_of_week }}">
        <x-input-label for="shift-start-{{ $day_of_week }}" :value="__(ucfirst($day_of_week))" />
        <div class="flex gap-4">
            <div>
                <x-input-label for="shift-start-{{ $day_of_week }}" :value="__('Starttijd')" />
                <input type="time" id="shift-start-{{ $day_of_week }}" name="shift-start-{{ $day_of_week }}" value="{{ $employee_scheduled_shift ? date('H:i', strtotime($employee_scheduled_shift->shift_start)) : '' }}">
                <x-input-error class="mt-2" :messages="$errors->get('shift-start-' . $day_of_week)" />
            </div>

            <div>
                <x-input-label for="shift-end-{{ $day_of_week }}" :value="__('Eindtijd')" />
                <input type="time" id="shift-end-{{ $day_of_week }}" name="shift-end-{{ $day_of_week }}" value="{{ $employee_scheduled_shift ? date('H:i', strtotime($employee_scheduled_shift->shift_end)) : '' }}">
                <x-input-error class="mt-2" :messages="$errors->get('shift-end-' . $day_of_week)" />
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
