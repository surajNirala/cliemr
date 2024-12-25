<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\QuickNoteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TestPrescribeController;
use App\Http\Controllers\MedicineLibraryController;
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
    Route::get('/users1', [UserController::class, 'users1'])->name('users1');
    Route::get('/users/create', [UserController::class, 'users_create'])->name('users_create');
    Route::post('/users/store', [UserController::class, 'users_store'])->name('users_store');

    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::get('/getusers', [UserController::class, 'getusers'])->name('getusers');
    Route::post('/users/store-new', [UserController::class, 'users_store_new'])->name('users_store_new');
    Route::get('users/edit/{id}', [UserController::class, 'users_edit'])->name('users_edit');  
    Route::post('users/update', [UserController::class, 'users_update'])->name('users_update');  
    Route::get('users/delete/{id}', [UserController::class, 'users_delete'])->name('users_delete'); 
    Route::get('users/permissions/{id}', [UserController::class, 'users_permissions'])->name('users_permissions'); 
    Route::post('users/permissions/save', [UserController::class, 'users_permissions_save'])->name('users_permissions_save'); 
    Route::post('users/change-status/{id}', [UserController::class, 'users_change_status'])->name('users_change_status');
    
    Route::get('/role-permission', [RolePermission::class, 'role_permission'])->name('role_permission');
    Route::post('/fetch-user', [RolePermission::class, 'fetch_user'])->name('fetch_user');

    /************* RoleController *************/    
    Route::get('/roles', [RoleController::class, 'roles'])->name('roles');//->middleware('permission:roles');
    Route::get('/getroles', [RoleController::class, 'getroles'])->name('getroles');
    Route::post('roles/store', [RoleController::class, 'roles_store'])->name('roles_store');
    Route::post('roles/change-status/{id}', [RoleController::class, 'roles_change_status'])->name('roles_change_status');
    Route::get('roles/delete/{id}', [RoleController::class, 'roles_delete'])->name('roles_delete');    
    Route::get('roles/edit/{id}', [RoleController::class, 'roles_edit'])->name('roles_edit');    
    
    /************* PermissionController *************/    
    Route::get('/permissions', [PermissionController::class, 'permissions'])->name('permissions');//->middleware('permission:permissions');
    Route::get('/getpermissions', [PermissionController::class, 'getpermissions'])->name('getpermissions');
    Route::post('permissions/store', [PermissionController::class, 'permissions_store'])->name('permissions_store');
    Route::post('permissions/change-status/{id}', [PermissionController::class, 'permissions_change_status'])->name('permissions_change_status');
    Route::get('permissions/delete/{id}', [PermissionController::class, 'permissions_delete'])->name('permissions_delete');    
    Route::get('permissions/edit/{id}', [PermissionController::class, 'permissions_edit'])->name('permissions_edit'); 

    /************* QuickNoteController *************/  
    // Route::middleware(['role:SUPERADMIN,ADMIN,DOCTOR'])->group(function () {  
        Route::get('custom-templates/quicknotes', [QuickNoteController::class, 'quicknotes'])->name('quicknotes');
        Route::get('custom-templates/getquicknotes', [QuickNoteController::class, 'getquicknotes'])->name('getquicknotes');
        Route::post('custom-templates/quicknotes/store', [QuickNoteController::class, 'quicknotes_store'])->name('quicknotes_store');
        Route::post('custom-templates/quicknotes/change-status/{id}', [QuickNoteController::class, 'quicknotes_change_status'])->name('quicknotes_change_status');
        Route::get('custom-templates/quicknotes/delete/{id}', [QuickNoteController::class, 'quicknotes_delete'])->name('quicknotes_delete');    
        Route::get('custom-templates/quicknotes/edit/{id}', [QuickNoteController::class, 'quicknotes_edit'])->name('quicknotes_edit');  
    // });  

    
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
    
    /************* ComplaintController *************/    
    Route::get('complaints', [ComplaintController::class, 'complaints'])->name('complaints');
    Route::get('getcomplaints', [ComplaintController::class, 'getcomplaints'])->name('getcomplaints');
    Route::post('complaints/store', [ComplaintController::class, 'complaints_store'])->name('complaints_store');
    Route::post('complaints/change-status/{id}', [ComplaintController::class, 'complaints_change_status'])->name('complaints_change_status');
    Route::get('complaints/delete/{id}', [ComplaintController::class, 'complaints_delete'])->name('complaints_delete');    
    Route::get('complaints/edit/{id}', [ComplaintController::class, 'complaints_edit'])->name('complaints_edit');    
    
    /************* DiagnosisController *************/    
    Route::get('diagnosis', [DiagnosisController::class, 'diagnosis'])->name('diagnosis');
    Route::get('getdiagnosis', [DiagnosisController::class, 'getdiagnosis'])->name('getdiagnosis');
    Route::post('diagnosis/store', [DiagnosisController::class, 'diagnosis_store'])->name('diagnosis_store');
    Route::post('diagnosis/change-status/{id}', [DiagnosisController::class, 'diagnosis_change_status'])->name('diagnosis_change_status');
    Route::get('diagnosis/delete/{id}', [DiagnosisController::class, 'diagnosis_delete'])->name('diagnosis_delete');    
    Route::get('diagnosis/edit/{id}', [DiagnosisController::class, 'diagnosis_edit'])->name('diagnosis_edit');    
    
    /************* NoteController *************/    
    Route::get('notes', [NoteController::class, 'notes'])->name('notes');
    Route::get('getnotes', [NoteController::class, 'getnotes'])->name('getnotes');
    Route::post('notes/store', [NoteController::class, 'notes_store'])->name('notes_store');
    Route::post('notes/change-status/{id}', [NoteController::class, 'notes_change_status'])->name('notes_change_status');
    Route::get('notes/delete/{id}', [NoteController::class, 'notes_delete'])->name('notes_delete');    
    Route::get('notes/edit/{id}', [NoteController::class, 'notes_edit'])->name('notes_edit'); 
    
    
     /************* MedicineController *************/    
     Route::get('medicines', [MedicineController::class, 'medicines'])->name('medicines')->middleware('permission:medicines');
     Route::get('getmedicines', [MedicineController::class, 'getmedicines'])->name('getmedicines');
     Route::post('medicines/store', [MedicineController::class, 'medicines_store'])->name('medicines_store');
     Route::post('medicines/change-status/{id}', [MedicineController::class, 'medicines_change_status'])->name('medicines_change_status');
     Route::get('medicines/delete/{id}', [MedicineController::class, 'medicines_delete'])->name('medicines_delete');    
     Route::get('medicines/edit/{id}', [MedicineController::class, 'medicines_edit'])->name('medicines_edit');    
 

    /************* MedicineLibraryController *************/    
    Route::get('/medicinelibraries', [MedicineLibraryController::class, 'medicinelibraries'])->name('medicinelibraries');
    Route::get('/getmedicinelibraries', [MedicineLibraryController::class, 'getmedicinelibraries'])->name('getmedicinelibraries');
    Route::post('medicinelibraries/store', [MedicineLibraryController::class, 'medicinelibraries_store'])->name('medicinelibraries_store');
    Route::get('medicinelibraries/edit/{id}', [MedicineLibraryController::class, 'medicines_edit'])->name('medicines_edit');  
    Route::post('medicinelibraries/update', [MedicineLibraryController::class, 'medicinelibraries_update'])->name('medicinelibraries_update');
    Route::post('medicinelibraries/change-status/{id}', [MedicineLibraryController::class, 'medicinelibraries_change_status'])->name('medicinelibraries_change_status');
    Route::get('medicinelibraries/delete/{id}', [MedicineLibraryController::class, 'medicinelibraries_delete'])->name('medicinelibraries_delete');    
 

});

require __DIR__.'/auth.php';
