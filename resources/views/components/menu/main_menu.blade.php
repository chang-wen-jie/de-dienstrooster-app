@auth
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>

    <x-nav-link :href="route('calendar')" :active="request()->routeIs('calendar')">
        {{ __('Kalender') }}
    </x-nav-link>

    @if (auth()->user()->account_type === 'admin')
        <x-nav-link :href="route('admin')" :active="request()->routeIs('admin')">
            {{ __('Administratief') }}
        </x-nav-link>
    @endif
@endauth
