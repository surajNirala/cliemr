<?php

namespace App\Http\Controllers\UserManagment;

use App\Models\Role;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function users1(Request $request){
        $data['users'] = User::latest('created_at')->paginate(10);
        return view('user_management.users1.list',$data);
    }

    function users_create(Request $request){
        // $data['users'] = User::latest('created_at')->get();
        $data['users'] = '';
        return view('user_management.users.create',$data);
    }

    function users_store(Request $request){
        try {
            $username = $request->username;
            $crn = $request->crn;
            $image = $request->image;
            $name = $request->name;
            $email = $request->email;
            $phone = $request->phone;
            $password = Hash::make($request->password);
            $gender = $request->gender;
            $signature_text = $request->signature_text;
            $dob = date("Y-m-d", strtotime($request->dob));
            $signature_image = $request->signature_image;
            $speciality_id = $request->speciality_id;
            $userArr = [
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
            ];
            $user = User::create($userArr);
            $userProfileArr = [
                'user_id' => $user->id,
                'gender' => $gender,
                'signature_text' => $signature_text,
                'dob' => $dob,
                'speciality_id' => $speciality_id,
            ];
            $profile = Profile::create($userProfileArr);
            return back()->with('success', 'User created successfully');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    function users(Request $request)
    {
        $data['title'] = 'Users';
        return view('user_management.users.list',$data);
    }

    public function getusers(Request $request)
    {
        try {
            $columns = [
                'users.id as user_id',
                DB::raw('COUNT(DISTINCT users.id) as total'),
                DB::raw('MAX(users.name) as person_name'),
                DB::raw('MAX(users.username) as username'),
                DB::raw('MAX(users.email) as email'),
                DB::raw('MAX(users.phone) as phone'),
                DB::raw('MAX(users.image) as image'),
                DB::raw('MAX(users.status) as status'),
                DB::raw('MAX(users.created_at) as created_at'),
                DB::raw('MAX(profiles.gender) as gender'),
                DB::raw('MAX(profiles.signature_text) as signature_text'),
                DB::raw('MAX(profiles.dob) as dob'),
                DB::raw('MAX(profiles.signature_image) as signature_image'),
                DB::raw('MAX(profiles.speciality_id) as speciality_id'),
                DB::raw('MAX(specialities.name) as specialist_name'),
                DB::raw('MAX(roles.name) as role_name'),
            ];
            
            $query = User::select($columns)
                ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
                ->leftJoin('specialities', 'users.id', '=', 'specialities.user_id')
                ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id') // Join roles table
                ->groupBy('users.id'); // Group by user ID
            
            // Add search functionality
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.username', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%")
                        ->orWhere('users.phone', 'like', "%{$search}%")
                        ->orWhere('profiles.gender', 'like', "%{$search}%")
                        ->orWhere('profiles.signature_text', 'like', "%{$search}%")
                        ->orWhere('profiles.dob', 'like', "%{$search}%")
                        ->orWhere('specialities.name', 'like', "%{$search}%")
                        ->orWhere('roles.name', 'like', "%{$search}%");
                });
            }
            
            // Sorting functionality
            if ($request->has('order')) {
                $columns = [
                    'user_id', 'person_name', 'username',
                    'email', 'phone','image', 'status', 'created_at',
                    'gender', 'signature_text', 'dob', 'signature_image',
                    'speciality_id', 'specialist_name', 'role_name'
                ];
                $columnIndex = $request->order[0]['column'] ?? 0; // Default to column index 0
                $sortColumn = $columns[$columnIndex] ?? 'created_at'; // Default to 'created_at'
                $sortDirection = $request->order[0]['dir'] ?? 'desc'; // Default to 'desc'
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc'); // Default order
            }
            
            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            
            $totalRecords = $query->count(); // Total records without filtering
            $totalFilteredRecords = $query->first()->total;
            $data = $query->skip($start)->take($length)->get(); // Paginated data
            
            // DataTables JSON response
            return response()->json([
                "draw" => $request->draw, // Draw counter
                "recordsTotal" => $totalFilteredRecords, // Total records
                "recordsFiltered" => $totalFilteredRecords, // Total records after filtering
                "data" => $data, // Data
            ]);
            
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    
    public function users_store_new(Request $request)
    {
        DB::beginTransaction(); // Start transaction
        try {
            // Validate the form inputs
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'gender' => 'required|string',
                'dob' => 'required|date',
                'role_id' => 'required|integer|exists:roles,id',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|digits:10|unique:users,phone',
                'password' => 'required|string|min:8',
                'speciality_id' => 'required|integer|exists:specialities,id',
                'file' => 'nullable|mimes:jpg,png,jpeg,gif|max:2048',
                'signature_text' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle file upload
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $foldername = 'custom_data';
                $directoryPath = public_path($foldername); // Define the directory path
                // Check if the directory exists
                if (!file_exists($directoryPath)) {
                    // Create the directory with 777 permissions
                    mkdir($directoryPath, 0777, true);
                    // Verify permissions
                    chmod($directoryPath, 0777);
                }
                // Define the file path
                $fileName = time() . '_' . $file->getClientOriginalName(); // Generate a unique file name
                $filePath = $foldername . '/' . $fileName;
                // Move the uploaded file to the directory
                $file->move($directoryPath, $fileName);
            }

            $role_id = $request->role_id;
            $name = $request->name;
            $username = generateUniqueUsername($name);
            $email = $request->email;
            $phone = $request->phone;
            $password = Hash::make($request->password);
            $user_id = $request->user_id;
            $gender = $request->gender;
            $signature_text = $request->signature_text;
            $dob = date("Y-m-d", strtotime($request->dob));
            $speciality_id = $request->speciality_id;
            $userArr = [
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'image' => $filePath,
            ];
            $user = User::create($userArr);

            $userProfileArr = [
                'user_id' => $user->id,
                'gender' => $gender,
                'signature_text' => $signature_text,
                'dob' => $dob,
                'speciality_id' => $speciality_id,
            ];

            $profile = Profile::create($userProfileArr);
            
            //Attach the role to the user
            $user = User::find($user->id);
            $role = Role::find($role_id);
            $user->roles()->attach($role);

            DB::commit();

            $response = [
                'success' => true,
                'message' => 'User information successfully saved!',
            ];
            return response()->json($response);
        } catch (\Exception $th) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response);
        }
    }

    public function users_update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'gender' => 'required|string',
                'dob' => 'required|date',
                'role_id' => 'required|integer|exists:roles,id',
                'email' => 'required|email|unique:users,email,' . $request->user_id,
                'phone' => 'required|digits:10|unique:users,phone,' . $request->user_id,
                'password' => 'nullable|string|min:8',
                'speciality_id' => 'nullable|integer|exists:specialities,id',
                'file' => 'nullable|mimes:jpg,png,jpeg,gif|max:2048',
                'signature_text' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle file upload
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $foldername = 'custom_data';
                $directoryPath = public_path($foldername); // Define the directory path
                // Check if the directory exists
                if (!file_exists($directoryPath)) {
                    // Create the directory with 777 permissions
                    mkdir($directoryPath, 0777, true);
                    // Verify permissions
                    chmod($directoryPath, 0777);
                }
                // Define the file path
                $fileName = time() . '_' . $file->getClientOriginalName(); // Generate a unique file name
                $filePath = $foldername . '/' . $fileName;
                // Move the uploaded file to the directory
                $file->move($directoryPath, $fileName);
            }
            $role_id = $request->role_id;
            $name = $request->name;
            $username = generateUniqueUsername($name);
            $email = $request->email;
            $phone = $request->phone;
            $password = Hash::make($request->password);
            $user_id = $request->user_id;
            $gender = $request->gender;
            $signature_text = $request->signature_text;
            $dob = date("Y-m-d", strtotime($request->dob));
            $speciality_id = $request->speciality_id;

            $userArr = [
                // 'username' => $username,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'image' => $filePath,
            ];

            $user = User::find($request->user_id);
            if (!$user) {
                $response = [
                    'success' => false, 
                    'message' => 'User not found'
                ];
                return response()->json($response);
            }
            
            $userProfileArr = [
                'user_id' => $user->id,
                'gender' => $gender,
                'signature_text' => $signature_text,
                'dob' => $dob,
                'speciality_id' => $speciality_id,
            ];

            User::where('id',$request->user_id)->update($userArr);
            $exist_profile = Profile::where('user_id',$request->user_id)->first();
            if (empty($exist_profile)) {
                Profile::create($userProfileArr);
            }else{
                Profile::where('user_id',$request->user_id)->update($userProfileArr);
            }
            DB::table('role_user')->where('user_id',$user->id)->delete();
            //Attach the role to the user
            $user = User::find($user->id);
            $role = Role::find($role_id);
            $user->roles()->attach($role);
            DB::commit();
            $response = [
                'success' => true, 
                'message' => 'User updated successfully.'
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'success' => false, 
                'message' => $e->getMessage()
            ];
            return response()->json($response);
        }
    }


    public function users_edit($id)
    {
        try {
            $columns = [
                'users.id as user_id',
                'users.name as person_name',
                'users.username as username',
                'users.email as email',
                'users.phone as phone',
                'users.image as image',
                'users.status as status',
                'users.created_at as created_at',
                'profiles.gender as gender',
                'profiles.signature_text as signature_text',
                'profiles.dob as dob',
                'profiles.signature_image as signature_image',
                'profiles.speciality_id as speciality_id',
                'specialities.name as specialist_name',
                'roles.name as role_name',
                'roles.id as role_id',
            ];
            
            $user = User::select($columns)
                ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
                ->leftJoin('specialities', 'users.id', '=', 'specialities.user_id')
                ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('users.id',$id)
                ->first();

            if ($user) {
                $response = [
                    'status' => true, 
                    'data'   => $user, 
                    'getAllActiveSpeciality' => getAllActiveSpeciality(),
                    'getAllActiveRoles' => getAllActiveRoles(),
                    'message'=> 'User details.'
                ];
                return response()->json($response);
            }
            $response = [
                'status' => false, 
                'message' => 'User not found'
            ];
            return response()->json($response);
    
        } catch (\Exception $e) {
            $response = [
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ];
            return response()->json($response);
        }
    }

    public function users_delete($id)
    {
        DB::beginTransaction(); // Start the transaction
        try {
            // Find the user
            $user = User::find($id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }
    
            // Delete related profile
            $profileDeleted = Profile::where('user_id', $id)->delete();
    
            // Delete user
            $user->delete();
    
            DB::commit(); // Commit the transaction
            return response()->json(['success' => true, 'message' => 'User Deleted Successfully.']);
    
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function users_change_status($id, Request $request)
    {
        $user = User::find($id);
        if ($user) {
            if($user->email == 'superadmin@gmail.com'){
                return response()->json(['success' => false,'message' => 'Not Allowed to inactive super admin.']);
            }
            $user->status = $request->status;
            $user->save();
            $message = "User Status Inactive Successfully.";
            if($request->status == 1){
                $message = "User Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function users_permissions($id)
    {
        try {
            $response = [
                'status' => true, 
                'getAllActivePermissions' => getAllActivePermissions(),
                'getAllPermissionIds' => getUserPermissions($id,true),
                'message'=> 'User permissions.'
            ];
            return response()->json($response);
    
        } catch (\Exception $e) {
            $response = [
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ];
            return response()->json($response);
        }
    }

    public function users_permissions_save(Request $request)
    {
        $userId = $request->input('user_id');
        $permissions = $request->input('permissions', []);

        if (!$userId) {
            $response = [
                'success' => false, 
                'message' => 'User ID is required'
            ];
            return response()->json($response);
        }
        // Save permissions (assuming a many-to-many relationship)
        $user = User::find($userId);
        if (!$user) {
            $response = [
                'success' => false, 
                'message' => 'User not found'
            ];
            return response()->json($response);
        }
        $columns = [
            'roles.id as id',
        ];
        
        $role = Role::select($columns)
            ->leftJoin('role_user', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('users', 'users.id', '=', 'role_user.user_id')
            ->where('users.id',$userId)
            ->first();
        // Sync permissions (overwrite existing permissions)
        // $role->permissions()->attach([
        //     $treatment_by_doctor->id,
        //     $add_patient->id,
        //     $front_desk_permission->id,
        //     $front_desk_administration->id,
        //     $lab_management->id,
        //     $lab_administration->id,
        //     $record_patient_value->id,
        //     $branch_dministration->id,
        //     $delete_bills->id,
        //     $update_unit_price_while_billing->id,
        // ]);
        $role->permissions()->sync($permissions);
        $response = [
            'success' => true, 
            'message' => 'Permissions saved successfully'
        ];
        return response()->json($response);
    }
}
