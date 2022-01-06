<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                {{ __('Online store') }}
            </h2>
            @include('store.products.components.head_right')
        </div>
    </x-slot>

    <x-auth-validation-errors class="mb-3" :errors="$errors" />

    <div class="card my-4">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-3">
                            <btn-tumbnail linkImg="{{ $contextImage->getSize($product->poster) }}"
                                linkTumbnail="{{ $contextImage->getSize($product->poster, 'l') }}"
                                id="{{ $product->id }}"></btn-tumbnail>


                            <form class="mt-3" action="{{ route('store.order.addProduct', $product->id) }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">@lang('app.quantity')</span>
                                        <input type="number" min="1" max="{{ $product->quantity }}" name="quantity"
                                            value="1" class="form-control" />
                                    </div>
                                </div>
                                <button data-wait="Wait..." type="submit" class="btn btn-success w-100 text-light btn-wait-submit">
                                    <em class="fas fa-cart-plus"></em> {{ __('Add to cart') }}
                                </button>
                            </form>
                        </div>

                        <div class="col-sm-9">
                            <div class="card text-white bg-secondary mb-3">
                                <div class="card-header"><em class="far fa-caret-square-right"></em>
                                    {{ $product->category->name }}</div>
                                <div class="card-body">
                                    <h4 class="card-title">{{ $product->name }}</h4>
                                    <hr />
                                    <p class="card-text">
                                    <h4>@lang('app.description'): </h4>
                                    {{ $product->description }}
                                    </p>
                                    <p class="card-text mt-5">
                                    <h4>@lang('app.price'): @money($product->price)</h4>
                                    </p>
                                    <p class="card-text mt-4">
                                    <h4>@lang('app.quantity'): {{ $product->quantity }}</h4>
                                    </p>

                                    <p class="card-text mt-4">
                                        <hr />
                                    <h4>{{ _('All images') }}</h4>
                                    <div class="row text-center">
                                        @foreach ($product->images as $image)
                                            <div id="col_id_{{ $image->id }}" class="col-md-3 mt-3">
                                                <btn-tumbnail linkImg="{{ $image->url }}"
                                                    linkTumbnail="{{ $contextImage->getSize($image->url, 'b') }}"
                                                    id="{{ $image->id }}"></btn-tumbnail>
                                            </div>
                                        @endforeach
                                    </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <hr />
                    <h3 class="text-center mb-4"> {{ _('All Categories') }}</h3>

                    <div class="d-flex justify-content-evenly">
                        <a href="{{ route('store.products.index') }}" class="btn btn-info">
                            <em class="far fa-caret-square-right"></em> {{ _('All Products') }}
                        </a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('store.products.index', $cat->slug) }}" class="btn btn-info">
                                <em class="far fa-caret-square-right"></em> {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>


        </div>
    </div>
</x-app-layout>
