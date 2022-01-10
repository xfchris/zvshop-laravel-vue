<nav class="navbar navbar-expand-md navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="/">
            <x-application-logo width="36" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto mb-0">
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ env('APP_NAME') }}
                </x-nav-link>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav">

                <!-- Settings Dropdown -->
                @auth
                    <x-dropdown id="settingsDropdown">
                        <x-slot name="trigger">
                            {{ Auth::user()->name }}
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('users.edit', Auth::user()->id)">
                                {{ __('Edit account') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('payment.orders')">
                                {{ __('Order list') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-muted">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ms-4 text-muted">Register</a>
                    @endif
                @endif
            </ul>
        </div>
    </div>
</nav>
