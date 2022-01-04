<x-app-layout>
    <x-slot name="header">
        <div class="d-flex">
            <h2 class="h4 font-weight-bold mb-0">
                @lang('app.user_management.edit_user')
            </h2>
        </div>
    </x-slot>


    <x-auth-session-success class="mb-3"/>
    <x-auth-validation-errors class="mb-3" :errors="$errors" />


    <div class="card my-4">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <h4 class="m-0">{{ __('Account data') }}</h4>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="form-label mt-2">Full name</label>
                            <input type="text" class="form-control" id="name" value="{{ $user->name }}" name="name"
                                maxlength="80" aria-describedby="name" placeholder="Enter name" required>
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

                            <select class="form-select" name="id_type" id="id_type">
                                <option>Select a type...</option>
                                @foreach (App\Constants\AppConstants::TYPE_DOCUMENT as $typeDocument)
                                    <option value="{{ $typeDocument }}"
                                        {{ $user->id_type == $typeDocument ? 'selected' : '' }}>
                                        @lang('app.type_document.'.$typeDocument)
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="id_number" class="form-label mt-2">ID Number</label>
                            <input type="number" class="form-control" id="id_number" value="{{ $user->id_number }}"
                                name="id_number" maxlength="15" min="1" max="99999999999" aria-describedby="id_number" placeholder="Enter id">
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


                <a class="btn btn-secondary mt-4 me-2 btn-wait-submit text-light" href="{{ route('users.index') }}"
                    data-wait="Wait...">Cancel</a>
                <button type="submit" class="btn btn-success mt-4 btn-wait-submit text-light" data-wait="Wait...">Update
                    data</button>

            </form>
        </div>
    </div>


</x-app-layout>
