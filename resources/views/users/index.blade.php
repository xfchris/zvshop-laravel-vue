
<x-app-layout>

    <x-slot name="header">
        <div class="d-flex">
            <h2 class="h4 font-weight-bold mb-0">
                {{ __('app.user_management.title') }}
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

            <table class="table table-bordered table-striped" aria-describedby="Users table">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="width:5%">Id</th>
                        <th scope="col" style="width:25%">Full name</th>
                        <th scope="col" style="width:20%">Email</th>
                        <th scope="col" style="width:15%">Banned until</th>
                        <th scope="col" style="width:10%">Roles</th>
                        <th scope="col" style="width:10%">Date</th>
                        <th scope="col" style="width:15%">Options</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <small>{{ $user->check_banned_until }}</small>
                            </td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-xs btn-primary text-light py-0 d-flex align-items-center">
                                        <i class="fas fa-edit"></i> <span class="ms-1">Edit</span>
                                    </a>
                                    <btn-block-user link="{{ route('api.users.activateInactivateUser', $user->id) }}"
                                        banned_until="{{ $user->check_banned_until }}">
                                        {{ $user->check_banned_until ? 'Unblock' : 'Block' }} user
                                    </btn-block-user>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {!! $users->links() !!}
            </div>
        </div>
    </div>
</x-app-layout>
