<?php use Carbon\Carbon; ?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Personeelsoverzicht') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table">
                        <tbody style="background-color: green">
                        Aanwezigen ({{ count($present_users) }})
                        @foreach($present_users as $present_user)
                            <tr>
                                <td>{{ $present_user->name }}</td>
                                <td>
                                    @php
                                        $date = null;
                                        $date1 = Carbon::parse($present_user->last_check_in);
                                        $date2 = Carbon::parse($present_user->last_check_out);

                                        $date1->greaterThan($date2) ? $date = $date1 : $date = $date2;
                                    @endphp

                                    {{ $session_role->id === 1 ? $date : $date->toDateString() }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.show', $present_user->id) }}" class="btn btn-primary btn-sm">
                                        Afwezig melden
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <table class="table" style="background-color: red">
                        Afwezigen ({{ count($absent_users) }})
                        <tbody>
                        @foreach($absent_users as $absent_user)
                            <tr>
                                <td>{{ $absent_user->name }}</td>
                                <td>
                                    @php
                                        $date = null;
                                        $date1 = Carbon::parse($absent_user->last_check_in);
                                        $date2 = Carbon::parse($absent_user->last_check_out);

                                        $date1->greaterThan($date2) ? $date = $date1 : $date = $date2;
                                    @endphp

                                    {{ $session_role->id === 1 ? $date : $date->toDateString() }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.show', $absent_user->id) }}" class="btn btn-primary btn-sm">
                                        Aanwezig melden
                                    </a>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
