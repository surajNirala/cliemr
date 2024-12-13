<?php

namespace App\Http\Controllers\UserManagment;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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
            $user_id = $request->user_id;
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
            // Base query
            $columns = [
                'users.id as user_id','users.name as person_name','users.username',
                'users.email','users.phone','users.status', 'users.created_at','profiles.gender',
                'profiles.signature_text','profiles.dob','profiles.signature_image',
                'profiles.speciality_id','specialities.name as specialist_name','roles.name as role_name'
            ];
            $query = User::select($columns)
                        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
                        ->leftJoin('specialities', 'users.id', '=', 'specialities.user_id')
                        ->leftJoin('roles', 'users.id', '=', 'roles.user_id');
            // Search functionality
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
                            'user_id','person_name','username',
                            'email','phone','status', 'created_at',
                            'gender','signature_text','dob','signature_image',
                            'speciality_id','specialist_name','role_name'
                        ];
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
}
