<?php use Carbon\Carbon; ?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Presentie') }} ({{ count($present_users) }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200">
                    <div class="min-w-full align-middle">
                        Aanwezigen ({{ count($present_users) }})
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <tbody class="bg-green-300 divide-y divide-gray-200 divide-solid">
                            @foreach($present_users as $present_user)
                                <tr class="bg-green">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $present_user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                    @php
                                        $date = null;
                                        $date1 = Carbon::parse($present_user->last_check_in);
                                        $date2 = Carbon::parse($present_user->last_check_out);

                                        $date1->greaterThan($date2) ? $date = $date1 : $date = $date2;
                                    @endphp

                                        {{ $session_role === 1 ? $date : $date->toDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        <a href="{{ route('users.show', $present_user->id) }}" class="btn btn-primary btn-sm">
                                            Afwezig melden
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        Afwezigen ({{ count($absent_users) }})
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <tbody class="bg-red-300 divide-y divide-gray-200 divide-solid">
                            @foreach($absent_users as $absent_user)
                                <tr class="bg-red">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        {{ $absent_user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        @php
                                            $date = null;
                                            $date1 = Carbon::parse($absent_user->last_check_in);
                                            $date2 = Carbon::parse($absent_user->last_check_out);

                                            $date1->greaterThan($date2) ? $date = $date1 : $date = $date2;
                                        @endphp

                                        {{ $session_role === 1 ? $date : $date->toDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                        <a href="{{ route('users.show', $absent_user->id) }}" class="btn btn-primary btn-sm">
                                            Aanwezig melden
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
