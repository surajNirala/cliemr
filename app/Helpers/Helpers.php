<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use App\Models\Service;
use App\Models\Language;
use App\Models\Medicine;
use App\Models\Permission;
use App\Models\Speciality;
use App\Models\MedicineType;
use App\Models\MedicineAdministration;
function getStaffs($id=null){
    $staffs = Staff::get();
    return $staffs;
}

function getRole($id,$info=null){
    $role = Role::select('roles.name')
                ->join('role_user', 'role_user.role_id', 'roles.id')
                ->where('role_user.user_id',$id)
                ->first();
    if(!empty($role)){
        if(!empty($info)){
            return $role;
        }
        return $role->name;
    }
    return "No Role";
}


function getAllActivePermissions(){
    $permissions = Permission::where('status', 1)->get();
    return $permissions;
}

function getUserPermissions($user_id, $status=false){
    $permissions = Permission::select(
        'permissions.name as permission_name',
        'permissions.id as permission_id',
        'role_user.role_id',
        'role_user.user_id'
    )
    ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
    ->join('role_user', 'permission_role.role_id', '=', 'role_user.role_id')
    ->where('role_user.user_id', $user_id)
    ->get();
    if ($status ==  true) {
        $ids = [];
        foreach ($permissions as $key => $permission) {
            $ids[] = $permission->permission_id;
        }
        return $ids;
    }
    return $permissions;
}

function getAllActiveRoles(){
    $roles = Role::get();
    return $roles;
}

function getAllActiveSpeciality(){
    $info = Speciality::where('status',1)->get();
    return $info;
}

function generateUniqueUsername($name)
{
    // Convert the name to lowercase, remove spaces, and special characters
    $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($name));
    
    // Append a random number to ensure uniqueness
    $username = $baseUsername . rand(1000, 9999);
    
    // Check if the username already exists
    while (User::where('username', $username)->exists()) {
        $username = $baseUsername . rand(1000, 9999);
    }
    
    return $username;
}

function getAllActiveMedicineType(){
    return MedicineType::where('status', 1)->get();
}

function getAllActiveMedicines(){
    return Medicine::latest('created_at')->where('status', 1)->get();
}

function getAllActiveMedicineAdministration(){
    return MedicineAdministration::latest('created_at')->where('status', 1)->get();
}

function baseURL(){
    return env('APP_URL');
    // return 'http://cliemr.corevista.in/';
}

function activeRoles(){
    return $roles = Role::where('status', 1)->pluck('name')->implode(',');
}

function titleBeforName(){
    return [
        1=>"Mr",
        2=>"Mrs",
        3=>"Ms",
        4=>"Dr",
        5=>"Md",
        6=>"Smt",
        7=>"Baby",
        8=>"Master",
        9=>"Sri",
        10=>"Kumari",
    ];
}

function ageType(){
    return [
        "Years",
        "Months",
        "Weeks",
        "Days",
    ];
}

function bloodGroups(){
    return ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
}

function getAllActiveLanguage(){
    return Language::latest('name')->where('status', 1)->get();
}


function getAllActiveService(){
    return Service::where('status', 1)->get();
}


