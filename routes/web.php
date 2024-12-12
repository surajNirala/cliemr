<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\QuickNoteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TestPrescribeController;
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


Route::get('clear-all', function () {
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:clear');
    dd("Cache is cleared");
});

Route::get('migrate-fresh-one-time', function(){
    \Artisan::call('migrate:fresh');
    \Artisan::call('db:seed');
    dd('Created fresh database and Seeder... ');
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

    /************* QuickNoteController *************/    
    Route::get('custom-templates/quicknotes', [QuickNoteController::class, 'quicknotes'])->name('quicknotes');
    Route::get('custom-templates/getquicknotes', [QuickNoteController::class, 'getquicknotes'])->name('getquicknotes');
    Route::post('custom-templates/quicknotes/store', [QuickNoteController::class, 'quicknotes_store'])->name('quicknotes_store');
    Route::post('custom-templates/quicknotes/change-status/{id}', [QuickNoteController::class, 'quicknotes_change_status'])->name('quicknotes_change_status');
    Route::get('custom-templates/quicknotes/delete/{id}', [QuickNoteController::class, 'quicknotes_delete'])->name('quicknotes_delete');    
    Route::get('custom-templates/quicknotes/edit/{id}', [QuickNoteController::class, 'quicknotes_edit'])->name('quicknotes_edit');    

    
    /************* AdviceController *************/    
    Route::get('custom-templates/advice', [AdviceController::class, 'advice'])->name('advice');
    Route::get('custom-templates/getadvice', [AdviceController::class, 'getadvice'])->name('getadvice');
    Route::post('custom-templates/advice/store', [AdviceController::class, 'advice_store'])->name('advice_store');
    Route::post('custom-templates/advice/change-status/{id}', [AdviceController::class, 'advice_change_status'])->name('advice_change_status');
    Route::get('custom-templates/advice/delete/{id}', [AdviceController::class, 'advice_delete'])->name('advice_delete');    
    Route::get('custom-templates/advice/edit/{id}', [AdviceController::class, 'advice_edit'])->name('advice_edit');
    
    /************* TestPrescribeController *************/    
    Route::get('custom-templates/testprescribes', [TestPrescribeController::class, 'testprescribes'])->name('testprescribes');
    Route::get('custom-templates/gettestprescribes', [TestPrescribeController::class, 'gettestprescribes'])->name('gettestprescribes');
    Route::post('custom-templates/testprescribes/store', [TestPrescribeController::class, 'testprescribes_store'])->name('testprescribes_store');
    Route::post('custom-templates/testprescribes/change-status/{id}', [TestPrescribeController::class, 'testprescribes_change_status'])->name('testprescribes_change_status');
    Route::get('custom-templates/testprescribes/delete/{id}', [TestPrescribeController::class, 'testprescribes_delete'])->name('testprescribes_delete');    
    Route::get('custom-templates/testprescribes/edit/{id}', [TestPrescribeController::class, 'testprescribes_edit'])->name('testprescribes_edit');    
 

});

require __DIR__.'/auth.php';
