

<div class="d-flex">

    <form action="{{ route('store.products.index', !empty($category) ? $category->slug : '') }}">
        <div class="input-group mw-400px">
            <input name="q" type="text" class="form-control" placeholder="Search..."
                value="{{ request()->get('q') ?? '' }}">
            <button class="btn btn-primary" type="submit"><em class="fas fa-search text-light"></em></button>
        </div>
    </form>

    <a href="{{ route('store.order.show') }}" class="btn pe-0">
        <em class="fas fa-shopping-cart"></em>
        <span class="badge bg-primary rounded-pill">{{ Auth::user()->order->total_products }}</span>
    </a>
</div>
