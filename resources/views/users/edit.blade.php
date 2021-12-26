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
            <form action="{{ route('admin.users.update',$user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name" class="form-label mt-2">Full name</label>
                            <input type="text" class="form-control" id="name" value="{{ $user->name }}" name="name"
                                maxlength="80"
                                aria-describedby="name" placeholder="Enter name" required>
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
                    <div class="col-sm-6  d-none">
                        <div class="form-group">
                            <label for="exampleSelect1" class="form-label mt-4">Rol del usuario</label>
                            <select class="form-select" id="exampleSelect1">
                                <option>Administrador</option>
                                <option>Cliente</option>
                            </select>
                        </div>
                    </div>
                </div>

                <a class="btn btn-secondary mt-4 me-2 btn-wait-submit text-light" href="{{ route('admin.users.index')}}" data-wait="Wait...">Cancel</a>
                <button type="submit" class="btn btn-success mt-4 btn-wait-submit text-light" data-wait="Wait...">Update data</button>

            </form>
        </div>
    </div>


</x-app-layout>
