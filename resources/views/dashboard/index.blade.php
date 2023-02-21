@extends('dashboard.layout')
@section('content')
    <?php   use Carbon\Carbon; ?>
    <table class="table" style="background-color: green">
        <thead>
            <tr class="table-warning">
                <td>ID</td>
                <td>Personeelsnaam</td>
                <td>Laatste activiteit</td>
                <td>Acties</td>
            </tr>
        </thead>

        <tbody>
        Aanwezigen ({{ count($present_users) }})
        @foreach($present_users as $present_user)
            <tr>
                <td>{{$present_user->id}}</td>
                <td>{{$present_user->name}}</td>
                <td>
                        <?php
                            $recent = null;
                            $date1 = Carbon::parse($present_user->latest_check_in);
                            $date2 = Carbon::parse($present_user->latest_check_out);

                            if ($date1->greaterThan($date2)) {
                                $recent = $date1;
                            } else {
                                $recent = $date2;
                            }
                        ?>
                        {{$recent}}
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
                <td>{{$absent_user->id}}</td>
                <td>{{$absent_user->name}}</td>
                <td>
                        <?php
                        $recent = null;
                        $date1 = Carbon::parse($absent_user->latest_check_in);
                        $date2 = Carbon::parse($absent_user->latest_check_out);

                        if ($date1->greaterThan($date2)) {
                            $recent = $date1;
                        } else {
                            $recent = $date2;
                        }
                        ?>
                    {{$recent}}
                </td>
                <td class="text-center">
                    <a href="{{ route('users.show', $absent_user->id) }}" class="btn btn-primary btn-sm">
                        Aanwezig melden
                    </a>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
