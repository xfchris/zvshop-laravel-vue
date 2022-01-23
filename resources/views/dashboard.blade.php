<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold  mb-0">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">

            <h2 class="text-center mb-5">Main menu</h2>

            <div class="m-4">
                <div class="row">
                    @if (Auth::user()->hasAnyRole(['admin']))
                        <div class="col-sm-3">
                            <a href="{{ route('users.index') }}"
                                class="btn btn-lg btn-outline-danger text-lightx w-100">
                                <i class="fas fa-users"></i>
                                <div>@lang('app.user_management.title')</div>
                            </a>
                        </div>
                    @endif

                    @if (Auth::user()->hasAnyRole([config('permission.roles.admin.name')]))
                        <div class="col-sm-3">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-lg btn-outline-info text-lightx w-100">
                                <i class="fas fa-users"></i>
                                <div>@lang('app.product_management.title')</div>
                            </a>
                        </div>
                    @endif

                    @if (Auth::user()->hasAnyRole([config('permission.roles.admin.name')]))
                        <div class="col-sm-3">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-lg btn-outline-primary text-lightx w-100">
                                <i class="fas fa-chart-line"></i>
                                <div>@lang('app.reports.title')</div>
                            </a>
                        </div>
                    @endif

                    @if (Auth::user()->hasAnyPermission([config('permission.names.store_show_products')]))
                        <div class="col-sm-3">
                            <a href="{{ route('store.products.index') }}" class="btn btn-lg btn-outline-success text-lightx w-100">
                                <i class="fas fa-cart-plus"></i>
                                <div>@lang('app.online_store.title')</div>
                            </a>
                        </div>
                    @endif

                </div>
            </div>


        </div>
    </div>
</x-app-layout>
