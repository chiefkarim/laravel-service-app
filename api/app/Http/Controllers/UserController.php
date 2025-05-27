<?php

// TODO: rethink how can i implement user-related logic without duplication and in a way that complies with Fortify implementation

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // Lister tous les utilisateurs
    public function index()
    {

        Gate::authorize('has-permission', ['permissions', 'read']);
        Gate::authorize('has-permission', ['users', 'read']);

        return User::with('permissions')->get();
    }

    // Afficher un utilisateur spécifique
    public function show(User $user)
    {
        Gate::authorize('has-permission', ['users', 'read']);
        Gate::authorize('has-permission', ['permissions', 'read']);
        $user->load('permissions');

        return response()->json($user);
    }

    // Créer un utilisateur
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, User $user)
    {
        Gate::authorize('has-permission', ['users', 'update']);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => "sometimes|email|unique:users,email,{$user->id}",
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    // Supprimer un utilisateur
    public function destroy(User $user)
    {
        Gate::authorize('has-permission', ['users', 'delete']);

        $user->delete();

        return response()->json(null, 204);
    }
}
