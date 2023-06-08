@php
    $is_admin = false;

    if(auth()->user()->account_type === 'admin')
    {
        $is_admin = true;
    }
@endphp

<x-app-layout>
    @push('meta')
        <meta http-equiv="refresh" content="20">
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Presentielijst') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">
                    <div class="min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Dienststatus</span>
                                    </th>

                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Personeel</span>
                                    </th>

                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Inchecktijd</span>
                                    </th>

                                    @if($is_admin)
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Acties</span>
                                    </th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 divide-solid">
                            @php
                                $sorted_employees = $active_employees->sort(function ($first_record, $second_record) {
                                    $first_record_presence = $first_record->last_check_in > $first_record->last_check_out;
                                    $second_record_presence = $second_record->last_check_in > $second_record->last_check_out;

                                    if ($first_record_presence === $second_record_presence) {
                                        return 0;
                                    }

                                    return $first_record_presence ? -1 : 1;
                                });
                            @endphp
                            @foreach($sorted_employees as $employee)
                                @php
                                    $employee_is_present = false;
                                    $check_in_time = Carbon\Carbon::parse($employee->last_check_in);
                                    $check_out_time = Carbon\Carbon::parse($employee->last_check_out);

                                    if ($check_in_time->greaterThan($check_out_time)) {
                                        $employee_is_present = true;
                                    }
                                @endphp
                                <tr class="{{ $employee_is_present ? 'bg-green-300' : 'bg-red-300' }}">
                                    <x-custom.employee-presence-state :employee="$employee" />
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $employee->name }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    @if($is_admin)
                                        {{ date('Y-m-d H:i:s', strtotime($employee->last_check_out)) }}
                                    @else
                                        {{ date('Y-m-d', strtotime($employee->last_check_out)) }}
                                    @endif
                                    </td>

                                    @if($is_admin)
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        <a href="{{ route('togglePresence', ['rfid' => $employee->rfid, 'api_key' => '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ']) }}" class="btn btn-primary btn-sm">
                                            {{ $employee_is_present ? __('Afwezig Melden') : __('Aanwezig Melden') }}
                                        </a>

                                        <a href="{{ route('logs', $employee->id) }}" class="btn btn-primary btn-sm">
                                            {{__('Logboek')}}
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                            {{ $active_employees->links() }}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <a href="{{ route('kiosk') }}" class="btn btn-primary btn-sm">
                        {{__('Kiosk Weergavemodus')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
