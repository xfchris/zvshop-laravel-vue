<form action="{{ route('store.products.index', (!empty($category) ? $category->slug : '')) }}">
    <div class="input-group mw-400px">
        <input name="q" type="text" class="form-control" placeholder="Search..." value="{{ $q ?? ''}}">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search text-light"></i></button>
    </div>
</form>
