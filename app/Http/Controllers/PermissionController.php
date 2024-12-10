<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    function permissions(Request $request){
        $data['title'] = 'Permission Management';
        $data['permissions'] = Permission::select('users.name as person_name', 'permissions.name as permission_name','permissions.description as permission_detail','permissions.status', 'permissions.created_at')
                            ->leftjoin('users', 'users.id', 'permissions.user_id')
                            ->latest('permissions.created_at')
                            ->paginate(10);
        return view('user_management.permissions.list',$data);
    }
}
