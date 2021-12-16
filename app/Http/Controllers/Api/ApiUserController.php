<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Yajra\Datatables\Datatables;

class ApiUserController extends Controller
{
    public function index(): mixed
    {
        $users = User::with('roles:id,name')->select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'status']);

        return Datatables::of($users)
            ->addColumn('action', function ($user) {
                return '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-xs btn-danger d-block text-light py-0"><i class="fas fa-edit"></i> Edit</a>';
            })
            ->addColumn('verified', function ($user) {
                return $user->status ? 'Active' : 'Inactive';
            })
            ->addColumn('roles', function ($user) {
                return array_reduce($user->roles->toArray(), function ($res, $item) {
                    $res .= $item['name'] . ',';
                    return trim($res, ',');
                });
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('d/m/Y');
            })
            ->removeColumn('email_verified_at')
            ->make(true);
    }
}
