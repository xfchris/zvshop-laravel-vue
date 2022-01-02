    <div class="card-product mx-auto my-2 card overflow-hidden">
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
