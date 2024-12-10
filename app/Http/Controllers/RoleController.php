<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function roles(Request $request){
        $data['title'] = 'Role Management';
        $data['roles'] = Role::select('users.name as person_name', 'roles.name as role_name','roles.status', 'roles.created_at')
                            ->leftjoin('users', 'users.id', 'roles.user_id')
                            ->latest('roles.created_at')
                            ->paginate(10);
        return view('user_management.roles.list',$data);
    }

    // function users_create(Request $request){
    //     // $data['users'] = User::latest('created_at')->get();
    //     $data['users'] = '';
    //     return view('user_management.users.create',$data);
    // }


    // function users_store(Request $request){
    //     try {
    //         $username = $request->username;
    //         $crn = $request->crn;
    //         $image = $request->image;
    //         $name = $request->name;
    //         $email = $request->email;
    //         $phone = $request->phone;
    //         $password = Hash::make($request->password);
    //         $user_id = $request->user_id;
    //         $gender = $request->gender;
    //         $signature_text = $request->signature_text;
    //         $dob = date("Y-m-d", strtotime($request->dob));
    //         $signature_image = $request->signature_image;
    //         $speciality_id = $request->speciality_id;
    //         $userArr = [
    //             'username' => $username,
    //             'name' => $name,
    //             'email' => $email,
    //             'phone' => $phone,
    //             'password' => $password,
    //         ];
    //         $user = User::create($userArr);
    //         $userProfileArr = [
    //             'user_id' => $user->id,
    //             'gender' => $gender,
    //             'signature_text' => $signature_text,
    //             'dob' => $dob,
    //             'speciality_id' => $speciality_id,
    //         ];
    //         $profile = Profile::create($userProfileArr);
    //         return back()->with('success', 'User created successfully');
    //     } catch (\Exception $th) {
    //         return back()->with('error', $th->getMessage());
    //     }
    // }
}
