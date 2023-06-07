@props(['employee'])
@if (isset($employee))
    @php $event_state = __('Roostervrij'); @endphp
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
    @foreach($employee->event as $event)
        @if(date('Y-m-d', strtotime($event->start)) == date('Y-m-d'))
            @if($event->event_type === 'shift')
                @php
                    $event_state = $event->called_in_sick ? '<span class="text-danger">' . __('Ingeroosterd (Ziek)') . '</span><a href="'.route("reportRecovery", $employee->id).'">' . __('Beter melden') . '</a>' : __('Ingeroosterd');
                @endphp
            @endif
        @endif
    @endforeach
        {!! $event_state !!}
    </td>

@endif
