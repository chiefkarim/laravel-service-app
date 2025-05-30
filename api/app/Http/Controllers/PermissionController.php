<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function index()
    {

        Gate::authorize('has-permission', ['permissions', 'read']);

        return Permission::all();
    }

    public function show(Permission $permission)
    {
        Gate::authorize('has-permission', ['permissions', 'read']);

        return $permission;
    }

    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'resource' => 'required|string',
            'operation' => 'required|string',
        ]);

        Gate::authorize('has-permission', ['permissions', 'create']);

        $permission = Permission::firstOrCreate($request->only('resource', 'operation'));

        $user = \App\Models\User::findOrFail($request->input('user_id'));
        if (! $user->permissions->contains($permission->id)) {
            $user->permissions()->attach($permission->id);
        }

        return response()->json($permission, 201);
    }

    public function destroy(Permission $permission)
    {

        Gate::authorize('has-permission', ['permissions', 'delete']);

        $permission->delete();

        return response()->json(null, 204);
    }
}
