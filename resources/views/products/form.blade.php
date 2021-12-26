<div class="row">
    <div class="col-md-6">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name" class="form-label mt-2">Title</label>
                    <input type="text" class="form-control" id="name" value="{{ $product->name }}" name="name"
                        maxlength="80" aria-describedby="name" placeholder="Enter name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="quantity" class="form-label mt-2">Quantity</label>
                    <input type="number" class="form-control" id="quantity" value="{{ $product->quantity }}"
                        name="quantity" min="0" max="2000" aria-describedby="quantity" placeholder="Enter quantity"
                        required>
                </div>
            </div>
            <div class="col-md-6">
                <label for="price" class="form-label mt-2">Price</label>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" id="price" value="{{ $product->price }}"
                            name="price" min="0" max="200000000000" step=".01" maxlength="2" aria-describedby="price"
                            placeholder="Enter price" required>
                        <span class="input-group-text">{{ config('constants.currency') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-8">

                <div class="form-group">
                    <label for="category_id" class="form-label mt-2">Categories</label>

                    <select class="form-select" name="category_id" required>
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="name" class="form-label mt-2">Description</label>
                    <textarea rows="5" class="form-control" required name="description"
                        maxlength="2000">{{ $product->description }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="col-md-8">
            <div class="mb-5">
                <label for="image" class="form-label mt-2">Principal Photo</label>
                <input class="form-control" type="file" id="image" name="image" required>
            </div>
            @foreach ([1, 2, 3] as $fileAID)
                <div class="mb-2">
                    <label for="images_alt{{ $fileAID }}" class="form-label mt-2">
                        Alternative photo {{ $fileAID }}
                    </label>
                    <input class="form-control" type="file" id="images_alt{{ $fileAID }}" name="images_alt[]"
                        name="file{{ $fileAID }}">
                </div>
            @endforeach
        </div>
    </div>
</div>
