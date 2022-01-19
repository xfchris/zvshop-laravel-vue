<table class="table table-bordered table-striped" aria-describedby="Users table">
    <thead class="table-dark">
        <tr>
            <th scope="col" style="width:5%">Id</th>
            <th scope="col" >Information</th>
            <th scope="col" >Shipping Address</th>
            @if(Route::is('payment.details') )
                <th scope="col" style="width: 35%">Products</th>
            @endif
            <th scope="col" style="width:170px">Options</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>


                <td>
                    <div>
                        <div>
                            <b>Status</b>: <span class="badge bg-{{ $order->status == App\Constants\AppConstants::APPROVED ? 'success' : 'info' }}">{{ strtolower($order->status) }}</span>
                        </div>
                        <div><b>Total products</b>: {{ $order->TotalProducts }}</div>
                        <div><b>Payment date</b>: {{ $order->created_at->format('d/m/Y') }}</div>
                        <div class="mt-2"><b>Total price</b>: @money($order->totalAmount) ({{ $order->currency }})</div>
                    </div>
                </td>
                <td>
                    <div>
                        <div><b>@lang('app.order.name_receive')</b>: {{ $order->name_receive }}</div>
                        <div><b>@lang('app.order.address')</b>: {{ $order->address }}</div>
                        <div><b>@lang('app.order.phone')</b>: {{ $order->phone }}</div>
                    </div>
                </td>

                @if(Route::is('payment.details') )
                <td class="p-0">
                    @foreach ($order->payment->products as $product)
                    <a href="{{ route('store.products.show', $product['pivot']['product_id']) }}" class="text-decoration-none text-dark">
                        <div class="card border productInfo m-2">
                            <div class="card-body p-1">
                                <div><b>Name</b>: {{ $product['name'] }}</div>
                                <div><b>Quantity</b>: {{ $product['pivot']['quantity'] }}</div>
                                <div><b>Unit price</b>: @money($product['price'])</div>
                                <div><b>Total</b>: @money($product['price'] * $product['pivot']['quantity']) ({{ $order->currency }})</div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </td>
                @endif

                <td>
                    <div class="d-flex flex-column">
                        @if ($order->status == App\Constants\AppConstants::REJECTED || $order->status == App\Constants\AppConstants::EXPIRED)
                           <form action="{{ route('payment.retryPay', $order->id) }}" method="post">
                                @csrf
                                <btn-submit class="btn btn-xs btn-primary text-light py-0 d-flex align-items-center w-100 mb-2 justify-content-center">
                                    <em class="fas fa-credit-card"></em> <span class="ms-1">Retry payment</span>
                                </btn-submit>
                           </form>
                        @elseif ($order->payment->status == App\Constants\AppConstants::CREATED)
                            <a href="{{ $order->payment['processUrl'] }}" target="_blank"
                                class="btn btn-xs btn-success text-light py-0 d-flex align-items-center w-100 mb-2 justify-content-center">
                                <span class="ms-1">Continue Payment</span>
                            </a>
                        @endif

                        @if(!Route::is('payment.details'))
                            <a href="{{ route('payment.details', $order->id) }}" target="_blank"
                                class="btn btn-xs btn-secondary text-light py-0 d-flex align-items-center w-100 mb-2 justify-content-center">
                                <span class="ms-1">Payment details</span>
                            </a>
                        @endif

                    </div>
                </td>
            </tr>
        @endforeach

    </tbody>

</table>
