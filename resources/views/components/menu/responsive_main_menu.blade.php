@auth
    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-responsive-nav-link>

    <x-responsive-nav-link :href="route('admin')" :active="request()->routeIs('admin')">
        {{ __('Admin') }}
    </x-responsive-nav-link>
@endauth
