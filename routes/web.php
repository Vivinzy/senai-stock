<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/debug-csrf', function () {
    $request = Illuminate\Http\Request::create('/filament/login', 'GET');
    $route = app('router')->getRoutes()->match($request);

    return response()->json([
        'csrf' => csrf_token(),
        'session_id' => session()->getId(),
        'session_all' => session()->all(),
        'filament_login_route_action' => $route->getActionName(),
        'filament_login_middleware' => $route->gatherMiddleware(),
    ]);
});
