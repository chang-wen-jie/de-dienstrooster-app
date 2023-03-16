@props(['employee'])
@if (isset($employee))
    @php $status = __('Vrij'); @endphp
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
    @foreach($employee->presence as $event)
        @if(date('Y-m-d', strtotime($event->start)) == date('Y-m-d'))
            @if($event->on_duty)
                @php
                    $status = $event->sick ? __('Ziek') : __('Ingeroosterd');
                @endphp
            @endif
        @endif
    @endforeach
        {{$status}}
    </td>

@endif
