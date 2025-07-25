<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', function () {
    Log::info('Auth redirect received.');

    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function (Request $request) {
    $googleUser = Socialite::driver('google')->user();

    $user = User::where('email', $googleUser->email)->first();
    Log::info('user', [$user]);

    if ($user) {
        $user->update(['google_id' => $googleUser->id]);
    } else {
        $user = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'password' => Hash::make(Str::random(24)),
        ]);
    }

    Auth::login($user);
    $redirect = config('app.frontend').'/services';
    Log::info('redirect url ', [$redirect]);

    return redirect($redirect);
});
