<x-app-layout>
    <x-slot name="header">
        <div class="d-flex">
            <h2 class="h4 font-weight-bold mb-0">
                {{ __('User Management') }}
            </h2>
        </div>
    </x-slot>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
    @endif

    <div class="card my-4">
        <div class="card-body">

            <table class="table table-bordered table-striped ajax-datatables" aria-describedby="Users table"
                data-url="{!! route('api.users') !!}" data-url-columns="id,name,email,verified,created_at,action">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="width:5%">Id</th>
                        <th scope="col" style="width:30%">Full name</th>
                        <th scope="col" style="width:20%">Email</th>
                        <th scope="col" style="width:10%">Status</th>
                        <th scope="col" style="width:20%">Date</th>
                        <th scope="col" style="width:10%">Options</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</x-app-layout>
