<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                {{ __('Online store') }}
            </h2>
            @include('store.products.components.search')
        </div>

    </x-slot>

    <div class="card my-4">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-3">
                    <h3> {{ _('Categories') }}</h3>

                    <div class="list-group mt-3">
                        <a href="{{ route('store.products.index') }}"
                            class="list-group-item list-group-item-action list-group-item-info">
                            <em class="far fa-caret-square-right"></em> {{ _('All Products') }}
                        </a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('store.products.index', $cat->slug) }}"
                                class="list-group-item list-group-item-action list-group-item-info">
                                <em class="far fa-caret-square-right"></em> {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-9">
                    <h3>{{ $category ? $category->name : 'All Products' }} {{ $q ? '- ' . $q : '' }}</h3>
                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                @include('store.products.components.poster')
                            </div>
                        @endforeach
                    </div>


                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-5">
                        {!! $products->links() !!}
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
