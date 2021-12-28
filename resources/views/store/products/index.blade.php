<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                {{ __('Online store') }}
            </h2>

            <form action="">
                <div class="input-group mw-400px">
                    <input name="q" type="text" class="form-control" placeholder="Search..." value="{{ $q ?? ''}}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>

            </form>
        </div>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-3">
                    <h3>Categories</h3>

                    <div class="list-group mt-3">
                        <a href="{{ route('store.products.index') }}"
                            class="list-group-item list-group-item-action list-group-item-info">
                            <em class="far fa-caret-square-right"></em> All Products
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
                    <h3>{{ ($category ? $category->name : 'All Products')}} {{ ($q ? '- '.$q : '')}}</h3>
                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                <div class="card_product mx-auto my-2 card overflow-hidden">
                                    <a class="text-decoration-none text-dark d-block"
                                        href="{{ route('store.products.show', $product->id) }}">
                                        <img class="card-img-top"
                                            src="{{ $contextImage->getSize($product->poster, 'l') }}"
                                            alt="{{ $product->name }}" />
                                        <div class="d-flex justify-content-between text-light prices">
                                            <div class="bg-primary px-2">@money($product->price)</div>
                                        </div>
                                        <div class="card-body bg-light" title="{{ $product->name }}">
                                            <p class="text-truncate card-text">{{ $product->name }}</p>
                                        </div>
                                    </a>
                                </div>
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
