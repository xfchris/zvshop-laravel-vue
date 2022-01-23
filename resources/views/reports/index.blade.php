<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <a href="{{ route('store.products.index') }}" class="text-dark text-decoration-none">
                    {{ __('Reports') }}
                </a>
            </h2>
        </div>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">

            @if (Auth::user()->hasAnyPermission([config('permission.names.reports_general')]))
            <div class="border p-2 rounded">
                <form action="{{ route('admin.reports.general') }}" method="post" class="row">
                    <h5>General reports</h5>
                    @include('reports.form_range')
                </form>
            </div>
            @endif

            @if (Auth::user()->hasAnyPermission([config('permission.names.reports_sales')]))
            <div class="border p-2 rounded mt-4">
                <form action="{{ route('admin.reports.sales') }}" method="post" class="row">
                    <h5>Sales reports</h5>

                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="category" class="form-label mt-1">Categories</label>

                            <select class="form-select" name="category_id">
                                <option value="">All categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @include('reports.form_range')
                </form>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
