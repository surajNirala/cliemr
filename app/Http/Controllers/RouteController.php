<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RouteController extends Controller
{
    public function showRoutes()
    {
        // Get all registered routes
        $data['routes'] = Route::getRoutes();
        $data['permissions'] = Permission::pluck('name')->toArray();

        // Return a view displaying the routes
        return view('routes.index', $data);
    }

    public function showRoutesAuth()
    {
        // Get all registered routes
        $data['routes'] = Route::getRoutes();
        $data['permissions'] = Permission::pluck('name')->toArray();

        // Return a view displaying the routes
        return view('routes.list', $data);
    }

    public function routes_permissions_linked(Request $request, $permission)
    {
        try {
            
            $exist = Permission::where('name', $permission)->first();
            if(!empty($exist)){
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Permission already linked.']);
                } 
            return back()->with('error', "Already linked.");
            }else{
                $customArr = [
                    'name' => $permission,
                    'description' => $permission,
                    'user_id' => Auth::user()->id,
                ];
                $permission = Permission::create($customArr);
                $columns = [
                    'roles.id as id',
                ];
                $role = Role::select($columns)
                            ->leftJoin('role_user', 'roles.id', '=', 'role_user.role_id')
                            ->leftJoin('users', 'users.id', '=', 'role_user.user_id')
                            ->where('users.id', Auth::user()->id)
                            ->first();
                if($role->id == 1){
                    $columns = [
                        'roles.id as id',
                    ];
                    $adminrole = Role::select($columns)
                                ->leftJoin('role_user', 'roles.id', '=', 'role_user.role_id')
                                ->leftJoin('users', 'users.id', '=', 'role_user.user_id')
                                ->where('users.flag',1)
                                ->first();
                    $adminrole->permissions()->sync([$permission->id]);
                }
                if ($request->ajax()) {
                    return response()->json(['success' => true, 'message' => $permission->name.' Permission linked successfully!!!.']);
                } 
                return back()->with('success', $permission->name." Permission Linked successfully.");
            }
        } catch (\Exception $th) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $th->getMessage()]);
            } 
            return back()->with('error', $th->getMessage());
        }
    }
}

