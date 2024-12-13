<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    function roles(Request $request){
        $data['title'] = 'Role Management';
        return view('user_management.roles.list',$data);
    }

    public function getroles(Request $request)
    {
        try {
            // Base query
            $query = Role::select('roles.id as role_id','users.name as person_name', 'roles.name as role_name','roles.status', 'roles.created_at')
            ->leftJoin('users', 'users.id', '=', 'roles.user_id');
            // Search functionality
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('roles.name', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
                });
            }

            // Sorting functionality
            if ($request->has('order')) {
                $columns = ['role_id', 'person_name', 'role_name', 'status', 'created_at'];
                $columnIndex = $request->order[0]['column']; // Column index
                $sortColumn = $columns[$columnIndex] ?? 'created_at'; // Default to 'created_at'
                $sortDirection = $request->order[0]['dir'] ?? 'desc'; // Default to 'desc'
                $query->orderBy($sortColumn, $sortDirection);
            }else {
                // Default order by 'created_at' in descending order
                $query->orderBy('created_at', 'desc');
            }

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $totalRecords = $query->count(); // Total records without filtering
            $data = $query->skip($start)->take($length)->get(); // Paginated data

            // DataTables JSON response
            return response()->json([
                "draw" => $request->draw, // Draw counter
                "recordsTotal" => $totalRecords, // Total records
                "recordsFiltered" => $totalRecords, // Total records after filtering
                "data" => $data, // Data
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    function roles_store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }
        $customArr = [
            'name' => $request->name,
        ];
        if(!empty($request->role_id)){
            $role = Role::where('id', $request->role_id)->update($customArr);
        }else{
            $customArr['user_id'] = Auth::user()->id;
            $role = Role::create($customArr);
        }
        if ($role) {
            $message = 'Role Created Successfully!';
            if(!empty($request->role_id)){
                $message = 'Role Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Role!']);
        }
    }

    public function roles_change_status($id, Request $request)
    {
        $role = Role::find($id);
        if ($role) {
            $role->status = $request->status;
            $role->save();
            $message = "Role Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Role Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function roles_edit($id)
    {
        $role = Role::where('id',$id)->first();
        if ($role) {
            return response()->json(['success' => true,'message' => 'Fetch Role.', 'data' => $role]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function roles_delete($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return response()->json(['success' => true,'message' => 'Role Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}

