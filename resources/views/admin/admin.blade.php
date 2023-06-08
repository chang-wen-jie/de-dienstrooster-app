<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Personeelsoverzicht') }}
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
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Bedrijfsrol</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Personeelsnaam</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Accountstatus</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Meest recente activiteit</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Beheren</span>
                                </th>
                            </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            @foreach($employees as $employee)
                                <tr class="bg-white">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $employee->account_type === 'admin' ? 'Beheerder' : 'Medewerker' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $employee->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $employee->account_status === 'active' ? 'Actief' : 'Inactief' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        @php
                                            $employee_is_present = false;
                                            $check_in_time = Carbon\Carbon::parse($employee->last_check_in);
                                            $check_out_time = Carbon\Carbon::parse($employee->last_check_out);

                                            if ($check_in_time->greaterThan($check_out_time)) {
                                                $employee_is_present = true;
                                            }
                                        @endphp
                                        {{ $employee_is_present ? $check_in_time : $check_out_time }} <b>({{ $employee_is_present ? 'Ingecheckt' : 'Uitgecheckt' }})</b>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        <a href="{{ route('admin.edit', $employee->id)}}" class="btn btn-primary btn-sm">
                                            Acties
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            {{ $employees->links() }}
                            </tbody>

                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                <a href="{{ route('admin.create')}}" class="btn btn-primary btn-sm">
                                    Personeel Toevoegen
                                </a>
                            </td>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
