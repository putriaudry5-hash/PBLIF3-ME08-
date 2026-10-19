
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SessionAuthController;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| CSRF TOKEN UNTUK REACT
|--------------------------------------------------------------------------
*/

Route::get('/auth/csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
    ]);
});

/*
|--------------------------------------------------------------------------
| LOGIN ADMIN DAN TENANT
|--------------------------------------------------------------------------
*/

Route::post('/login/internal', [
    SessionAuthController::class,
    'login'
])->middleware('throttle:5,1');

/*
|--------------------------------------------------------------------------
| ROUTE YANG MEMBUTUHKAN LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth:web')->group(function () {

    // Memeriksa pengguna yang sedang login
    Route::get('/auth/me', [
        SessionAuthController::class,
        'me'
    ]);

    // Logout pengguna
    Route::post('/logout/internal', [
        SessionAuthController::class,
        'logout'
    ]);

});
