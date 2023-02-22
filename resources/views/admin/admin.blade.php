<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table" style="background-color: grey">
                        <thead>
                            <tr class="table-warning">
                                <td>ID</td>
                                <td>Rol</td>
                                <td>Naam</td>
                                <td>Status</td>
                                <td>Laatste activiteit</td>
                                <td></td>
                            </tr>
                        </thead>

                        <tbody>
                        Personelen ({{ count($users) }})
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->role_id === 1 ? 'Beheerder' : 'Medewerker' }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->active ? 'Actief' : 'Inactief' }}</td>
                                <td>
                                    @php
                                        $recent = null;
                                        $date1 = Carbon\Carbon::parse($user->last_check_in);
                                        $date2 = Carbon\Carbon::parse($user->last_check_out);
                                    @endphp

                                    {{ $date1->greaterThan($date2) ? $date1 : $date2 }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit', $user->id)}}" class="btn btn-primary btn-sm">
                                        Wijzigen
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
</x-app-layout>
