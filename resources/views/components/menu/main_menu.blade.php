@auth
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>

    <x-nav-link :href="route('users.admin')" :active="request()->routeIs('admin')">
        {{ __('Administratief') }}
    </x-nav-link>
@endauth
