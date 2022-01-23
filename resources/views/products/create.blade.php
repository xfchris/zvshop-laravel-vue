<x-app-layout>
    <x-slot name="header">
        <div class="d-flex">
            <h2 class="h4 font-weight-bold mb-0">
                @lang('app.product_management.create_product')
            </h2>
        </div>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                @include('products.form')

                <a class="btn btn-secondary mt-4 me-2 text-light" href="{{ route('admin.products.index') }}">Cancel</a>
                
                <btn-submit class="btn btn-success mt-4 text-light">
                    @lang('app.product_management.create_product')
                </btn-submit>

            </form>
        </div>
    </div>


</x-app-layout>
