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
                            <a href="{{ route('admin.users.index') }}"
                                class="btn btn-lg btn-outline-danger text-lightx w-100">
                                <i class="fas fa-users"></i>
                                <div>User management</div>
                            </a>
                        </div>
                    @endif

                    @if (Auth::user()->hasAnyRole([config('permission.roles.admin.name')]))
                        <div class="col-sm-3">
                            <a href="#" class="btn btn-lg btn-outline-info text-lightx w-100">
                                <i class="fas fa-users"></i>
                                <div>Product management</div>
                            </a>
                        </div>
                    @endif

                    @if (Auth::user()->hasAnyRole([config('permission.roles.admin.name')]))
                        <div class="col-sm-3">
                            <a href="#" class="btn btn-lg btn-outline-primary text-lightx w-100">
                                <i class="fas fa-chart-line"></i>
                                <div>Reports</div>
                            </a>
                        </div>
                    @endif

                    @if (Auth::user()->hasAnyRole([config('permission.roles.admin.name'), config('permission.roles.clients.name')]))
                        <div class="col-sm-3">
                            <a href="#" class="btn btn-lg btn-outline-success text-lightx w-100">
                                <i class="fas fa-cart-plus"></i>
                                <div>Online store</div>
                            </a>
                        </div>
                    @endif

                </div>
            </div>


        </div>
    </div>
</x-app-layout>
