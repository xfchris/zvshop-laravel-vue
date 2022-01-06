<div class="card bg-light mt-4">
    <div class="card-header">@lang('app.order.shipping_address')</div>
    <div class="card-body border">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name_receive" class="form-label">@lang('app.order.name_receive')</label>
                <input type="text" class="form-control" id="name_receive"
                    value="{{ $order->name_receive ?? Auth::user()->name }}" name="name_receive" maxlength="80"
                    aria-describedby="name_receive" placeholder="Enter name receive" required>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="address" class="form-label mt-2">@lang('app.order.address')</label>
                <textarea rows="2" class="form-control" required name="address" placeholder="Enter shipping address"
                    maxlength="300">{{ $order->address ?? Auth::user()->address }}</textarea>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <div class="form-group">
                <label for="phone" class="form-label mt-2">@lang('app.order.phone')</label>
                <input type="tel" class="form-control" id="phone" value="{{ $order->phone ?? Auth::user()->phone }}"
                    name="phone" min="0" max="2000" aria-describedby="phone" placeholder="Enter phone">
            </div>
        </div>
    </div>
</div>
