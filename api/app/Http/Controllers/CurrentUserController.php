<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @group Users
 * APIs for managing users.
 */
class CurrentUserController extends Controller
{

    /**
     * Get the current authenticated user.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user()->load('permissions');

        return $user;
    }
}
