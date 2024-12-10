<?php

namespace App\Http\Controllers\UserManagment;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function users(Request $request){
        $data['users'] = User::latest('created_at')->paginate(10);
        return view('user_management.users.list',$data);
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
}
