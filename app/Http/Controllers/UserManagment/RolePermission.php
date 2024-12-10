<?php

namespace App\Http\Controllers\UserManagment;

use App\Models\User;
use App\Models\Staff;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RolePermission extends Controller
{
    function role_permission(Request $request){
        $data['staffs']  = Staff::get();
        return view('user_management.users.role_permission',$data);
    }

    function fetch_user(Request $request){
        $user_id = $request->user_id;
        $data['user_info'] = User::find($user_id);
        $permission_ids = getUserPermissions($user_id,true);
        $data['permission_ids'] = $permission_ids;
        $data['user_details_html'] = view('user_management.users.fetch-user',$data)->render();
        return $data;
    }
}
