<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserManagment\RolePermission;
use App\Http\Controllers\UserManagment\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


route::get('assign', function(){
    User::create([
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ]);
    $user = User::find(1);
    $role = Role::where('name', 'Admin')->first();
    $user->roles()->attach($role);
    return "Done!!!";
});

Route::get('/', function () {
    return redirect(route('role_permission'));
});

Route::get('/dashboard', function () {
    return redirect(route('role_permission'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::get('/users/create', [UserController::class, 'users_create'])->name('users_create');
    Route::post('/users/store', [UserController::class, 'users_store'])->name('users_store');
    
    Route::get('/role-permission', [RolePermission::class, 'role_permission'])->name('role_permission');
    Route::post('/fetch-user', [RolePermission::class, 'fetch_user'])->name('fetch_user');

    /************* RoleController *************/    
    Route::get('/roles', [RoleController::class, 'roles'])->name('roles');
    Route::get('/roles/create', [RoleController::class, 'roles_create'])->name('roles_create');
    Route::post('/roles/store', [RoleController::class, 'roles_store'])->name('roles_store');

    /************* PermissionController *************/    
    Route::get('/permissions', [PermissionController::class, 'permissions'])->name('permissions');
    Route::get('/permissions/create', [PermissionController::class, 'permissions_create'])->name('permissions_create');
    Route::post('/permissions/store', [PermissionController::class, 'permissions_store'])->name('permissions_store');



});

require __DIR__.'/auth.php';
