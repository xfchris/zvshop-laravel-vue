<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <a href="{{ route('store.products.index') }}" class="text-dark text-decoration-none">
                    {{ __('app.online_store.title') }}
                </a>
            </h2>
            @include('store.products.components.head_right')
        </div>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">

            @if ($order->totalProducts == 0)
                <h3 class="text-center my-5 py-5 border-dashed">@lang('app.cart_empty')</h3>
            @else
                <div class="row">
                    <div class="col-sm-8">
                        <h4>@lang('app.order.product_list')</h4>

                        @foreach ($order->products as $product)

                            <div class="card mb-3 border order-item">
                                <div class="row g-0">
                                    <div class="col-md-2">
                                        <btn-tumbnail linkImg="{{ $product->poster }}" imgClass="border-0 p-0 m-2"
                                            linkTumbnail="{{ $contextImage->getSize($product->poster, 'b') }}"
                                            id="image{{ $product->id }}"></btn-tumbnail>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="card-body py-1 pe-1">

                                            <form method="POST"
                                                action="{{ route('store.order.deleteProduct', $product->id) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-xs float-end"><em
                                                        class="fas fa-times"></em></button>
                                            </form>

                                            <a class="link-dark text-decoration-none"
                                                href="{{ route('store.products.show', $product->id) }}">
                                                <h5 class="card-title mb-3 mt-1">{{ $product->name }}</h5>
                                            </a>
                                            <div class="my-4">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <input-quantity value="{{ $product->pivot->quantity }}"
                                                            maxquantity="{{ $product->quantity }}"
                                                            inputtitle="@lang('app.quantity')"
                                                            action="{{ route('store.order.addProduct', $product->id) }}"
                                                            _token="{{ csrf_token() }}">
                                                        </input-quantity>
                                                    </div>
                                                    <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                                        <h4 class="pe-2">
                                                            <strong>@money($product->price *
                                                                $product->pivot->quantity)</strong>
                                                        </h4>
                                                    </div>
                                                </div>



                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                        <form action="{{ route('store.order.deleteOrder') }}" method="post">
                            @csrf
                            @method('delete')

                            <button type="submit"
                                class="btn btn-outline-secondary mt-3 btn-wait-submit" data-wait="Wait...">
                                Empty cart <em class="fas fa-trash-alt"></em>
                            </button>
                        </form>
                    </div>

                    <div class="col-sm-4">
                        <form id="formPay" action="{{ route('payment.pay') }}" method="post">
                            @csrf
                            @include('store.orders.components.shipping_address_form')

                            @method('post')
                            <input type="hidden" value="{{ $order }}" name="orderVisible" />
                        </form>

                        <div class="card bg-light mt-4">
                            <div class="card-header">@lang('app.resume')</div>
                            <div class="card-body border">
                                <div class="row">
                                    <div class="col-sm-5 fs-5">@lang('app.quantity'):</div>
                                    <div class="col-sm-7 fs-5">{{ $order->total_products }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-5 fs-5">@lang('app.total')</div>
                                    <div class="col-sm-7 fs-5"> <strong>@money($order->total_amount)</strong></div>
                                </div>

                                <button form="formPay" type="submit"
                                    class="btn btn-success mt-3 w-100 text-light btn-lg btn-wait-submit"
                                    data-wait="Wait...">
                                    Pay <em class="fas fa-credit-card"></em>
                                </button>
                            </div>


                        </div>
                    </div>

                </div>
        </div>

        @endif


    </div>
    </div>
</x-app-layout>
