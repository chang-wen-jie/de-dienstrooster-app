@props(['employee'])
@if (isset($employee))
    @php $status = __('Roostervrij'); @endphp
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
    @foreach($employee->presence as $presence)
        @if(date('Y-m-d', strtotime($presence->start)) == date('Y-m-d'))
            @if($presence->status_id === 1)
                @php
                    $status = $presence->called_in_sick ? __('Ingeroosterd (Ziek)') : __('Ingeroosterd');
                @endphp
            @endif
        @endif
    @endforeach
        {{$status}}
    </td>

@endif
