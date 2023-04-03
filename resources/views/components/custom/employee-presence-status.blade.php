@props(['employee'])
@if (isset($employee))
    @php $status = __('Roostervrij'); @endphp
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
    @foreach($employee->events as $event)
        @if(date('Y-m-d', strtotime($event->start)) == date('Y-m-d'))
            @if($event->status_id === 1)
                @php
                    $status = $event->sick ? '<span class="text-danger">' . __('Ingeroosterd (Ziek)') . '</span><a href="'.route("reportRecovery", $employee->id).'">' . __('Beter melden') . '</a>' : __('Ingeroosterd');
                @endphp
            @endif
        @endif
    @endforeach
        {!! $status !!}
    </td>

@endif
