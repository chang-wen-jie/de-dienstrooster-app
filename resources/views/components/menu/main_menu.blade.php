@auth
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
    <x-nav-link :href="route('users.admin')" :active="request()->routeIs('admin')">
        {{ __('Admin') }}
    </x-nav-link>
    <x-nav-link :href="route('users.index')" :active="request()->routeIs('index')">
        {{ __('Users') }}
    </x-nav-link>
@endauth
