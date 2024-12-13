<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function getpermissions(Request $request)
    {
        try {
            // Base query
            $columns = ['permissions.id as permission_id','users.name as person_name', 'permissions.name as permission_name','permissions.description as permission_detail','permissions.status', 'permissions.created_at'];
            $query = Permission::select($columns)
                                ->leftjoin('users', 'users.id', 'permissions.user_id');
            // Search functionality
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('permissions.name', 'like', "%{$search}%")
                    ->orWhere('permissions.description', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
                });
            }

            // Sorting functionality
            if ($request->has('order')) {
                $columns = ['permission_id', 'person_name', 'permission_name', 'permission_detail', 'status', 'created_at'];
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

    function permissions_store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }
        $customArr = [
            'name' => $request->name,
            'description' => $request->description,
        ];
        if(!empty($request->permission_id)){
            $permission = Permission::where('id', $request->permission_id)->update($customArr);
        }else{
            $customArr['user_id'] = Auth::user()->id;
            $permission = Permission::create($customArr);
        }
        if ($permission) {
            $message = 'Permission Created Successfully!';
            if(!empty($request->permission_id)){
                $message = 'Permission Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Permission!']);
        }
    }

    public function permissions_change_status($id, Request $request)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->status = $request->status;
            $permission->save();
            $message = "Permission Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Permission Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function permissions_edit($id)
    {
        $permission = Permission::where('id',$id)->first();
        if ($permission) {
            return response()->json(['success' => true,'message' => 'Fetch Permission.', 'data' => $permission]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function permissions_delete($id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->delete();
            return response()->json(['success' => true,'message' => 'Permission Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }
}
