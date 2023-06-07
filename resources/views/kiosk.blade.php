<html>
    <meta http-equiv="refresh" content="20">
</html>

<article class="content">
    <header class="employee-counter">
        <span>
            {{ __('Er zijn momenteel') }} <b><u>{{ count($present_employees) }}</u></b> {{ __('personeel aanwezig') }}
        </span>
    </header>
    <div class="bubble-container">
        @foreach($active_employees as $employee)
            @php
                $employee_is_present = false;
                $check_in_time = Carbon\Carbon::parse($employee->last_check_in);
                $check_out_time = Carbon\Carbon::parse($employee->last_check_out);

                if ($check_in_time->greaterThan($check_out_time)) {
                    $employee_is_present = true;
                }
            @endphp
            <div class="bubble {{ $employee_is_present ? 'present' : '' }}">
                <h1>
                    {{ $employee->name }}
                </h1>
                <p>
                    {{ $employee_is_present ? __('Ingecheckt op') : __('Uitgecheckt op') }} {{ $employee->updated_at }}
                </p>
            </div>
        @endforeach
    </div>
</article>

<script>
    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape") {
            event.preventDefault();
            window.location.href = "{{ route('dashboard') }}";
        }
    });
</script>

<style>
    html,
    body {
        height: 100%;
        font-family: sans-serif;
    }
    body {
        margin: 0;
        background-color: rgb(32, 33, 36);
    }
    :root {
        --main: rgb(255, 255, 255);
        --secondary: rgb(154, 160, 166);
    }

    .content {
        display: grid;
        place-items: center;
    }

    .employee-counter {
        margin-top: 25px;
    }
    .employee-counter span {
        color: var(--main);
        font-size: 48px;
    }

    .bubble-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 25px;
        row-gap: 75px;
        column-gap: 75px;
    }

    .bubble {
        height: 75px;
        width: 500px;
        border-radius: 10px;
    }
    .bubble h1 {
        color: var(--main);
        text-align: center;
    }
    .bubble p {
        color: var(--secondary);
        float: left;
    }

    .present {
        background: linear-gradient(to right, rgb(52, 168, 83) 50%, rgb(48, 49, 52) 50%);
    }
    .bubble:not(.present) {
        background: linear-gradient(to right, rgb(48, 49, 52) 50%, rgb(234, 67, 53) 50%);
    }

    @media (max-width: 768px) {
        .bubble {
            height: 50px;
            width: 100%;
        }
    }
    @media (max-width: 576px) {
        .bubble {
            height: 40px;
            width: 100%;
        }

        .bubble-container {
            row-gap: 50px;
            column-gap: 50px;
        }
    }
</style>

