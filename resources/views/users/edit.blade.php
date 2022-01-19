<x-app-layout>
    <x-slot name="header">
        <div class="d-flex">
            <h2 class="h4 font-weight-bold mb-0">
                @lang('app.user_management.edit_user')
            </h2>
        </div>
    </x-slot>

    <div class="card my-4">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <h4 class="m-0">{{ __('Account data') }}</h4>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name" class="form-label mt-2">Name</label>
                            <input type="text" class="form-control" id="name" value="{{ $user->name }}" name="name"
                                maxlength="80" aria-describedby="name" placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="surname" class="form-label mt-2">Surname</label>
                            <input type="text" class="form-control" id="surname" value="{{ $user->surname }}" name="surname"
                                maxlength="80" aria-describedby="surname" placeholder="Enter surname" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label mt-2">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly />
                        </div>
                    </div>
                </div>



                <div class="row">
                    <h4 class="m-0 mt-5">{{ __('Personal information') }}</h4>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="form-label mt-2">Document type</label>

                            <select class="form-select" name="document_type" id="document_type">
                                <option>Select a type...</option>
                                @foreach (App\Constants\AppConstants::DOCUMENT_TYPE as $typeDocument)
                                    <option value="{{ $typeDocument }}"
                                        {{ $user->document_type == $typeDocument ? 'selected' : '' }}>
                                        @lang('app.DOCUMENT_TYPE.'.$typeDocument)
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="document" class="form-label mt-2">ID Number</label>
                            <input type="number" class="form-control" id="document" value="{{ $user->document }}"
                                name="document" maxlength="15" min="1" max="99999999999" aria-describedby="document" placeholder="Enter document">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="address" class="form-label mt-2">Address</label>
                            <input type="text" class="form-control" id="address" value="{{ $user->address }}"
                                name="address" maxlength="180" aria-describedby="address" placeholder="Enter address">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone" class="form-label mt-2">Phone</label>
                            <input type="number" class="form-control" name="phone" maxlength="30"
                                value="{{ $user->phone }}" placeholder="Enter phpne" />
                        </div>
                    </div>
                </div>

                <a class="btn btn-secondary mt-4 me-2text-light" href="{{ route('users.index') }}">Cancel</a>
                <btn-submit type="submit" class="btn btn-success mt-4 text-light">
                    Update data
                </btn-submit>

            </form>
        </div>
    </div>


</x-app-layout>
