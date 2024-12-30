<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    function patients(Request $request){
        $data['title'] = 'Patient';
        return view('patients.patients',$data);
    }

    public function getpatients(Request $request)
    {
        $columns = [
            'patients.id as patient_id',
            'patients.created_by',
            'patients.image',
            'patients.title',
            'patients.name',
            'patients.gender',
            'patients.age',
            'patients.created_at',
            'patients.dob',
            'patients.email',
            'patients.phone',
            'patients.phone2',
            'patients.address',
            'patients.city',
            'patients.pincode',
            'patients.blood_group',
            'patients.referred_by_title',
            'patients.referred_by_name',
            'patients.referred_by_speciality',
            'patients.language_id',
            'patients.status',
            'patients.flag',
        ];
        // Base query
        $query = Patient::select($columns);
        // Search functionality
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('patients.title', 'like', "%{$search}%")
                ->orWhere('patients.name', 'like', "%{$search}%")
                ->orWhere('patients.gender', 'like', "%{$search}%")
                ->orWhere('patients.age', 'like', "%{$search}%")
                ->orWhere('patients.dob', 'like', "%{$search}%")
                ->orWhere('patients.email', 'like', "%{$search}%")
                ->orWhere('patients.phone', 'like', "%{$search}%")
                ->orWhere('patients.phone2', 'like', "%{$search}%")
                ->orWhere('patients.address', 'like', "%{$search}%")
                ->orWhere('patients.city', 'like', "%{$search}%")
                ->orWhere('patients.pincode', 'like', "%{$search}%")
                ->orWhere('patients.blood_group', 'like', "%{$search}%");
            });
        }
        // Sorting functionality
        if ($request->has('order')) {
            $columns = [
                'patient_id',
                'created_by',
                'image',
                'title',
                'name',
                'gender',
                'age',
                'created_at',
                'dob',
                'email',
                'phone',
                'phone2',
                'address',
                'city',
                'pincode',
                'blood_group',
                'referred_by_title',
                'referred_by_name',
                'referred_by_speciality',
                'language_id',
                'status',
                'flag'
            ];
            $columnIndex = $request->order[0]['column'] ?? 0; // Default to column index 0
            $sortColumn = $columns[$columnIndex] ?? 'created_at'; // Default to 'created_at'
            $sortDirection = $request->order[0]['dir'] ?? 'desc'; // Default to 'desc'
            // print_r($sortColumn);
            // print_r($sortDirection);
            // die;
            $query->orderByRaw("ISNULL($sortColumn), $sortColumn $sortDirection");
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
    }

    function patients_store(Request $request){
        try {
            // Validate the form inputs
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'name' => 'required|string|max:255',
                'gender' => 'required|in:male,female,others',
                'age' => 'required|numeric',
                'phone' => 'required|numeric|digits:10',
                'language_id' => 'nullable|exists:languages,id',
                // 'dob' => 'required|date',
                // 'email' => 'nullable|email',
                // 'address' => 'nullable|string',
                // 'city' => 'nullable|string',
                // 'pincode' => 'nullable|numeric',
                // 'blood_group' => 'nullable|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
            $customArr = [
                'created_by' => Auth::user()->id,
                'title' => $request->title,
                'name' => $request->name,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'blood_group' => $request->blood_group,
                'language_id' => $request->language_id,
            ];
            if(!empty($request->dob)){
                $customArr['dob'] = date("Y-m-d", strtotime($request->dob));
            }
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
                $customArr['image'] = $filePath;
            }
            $patient = Patient::create($customArr);
            $response = [
                'success' => true,
                'patient_id' => $patient->id,
                'message' => 'Patient information saved successfully!',
            ];
            return response()->json($response);
        } catch (\Exception $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response);
        }
    }

    public function patients_change_status($id, Request $request)
    {
        $quickNote = QuickNote::find($id);
        if ($quickNote) {
            $quickNote->status = $request->status;
            $quickNote->save();
            $message = "Quick Note Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Quick Note Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function patients_edit($id)
    {
        $patient = Patient::where('id',$id)->first();
        if ($patient) {
            return response()->json(['success' => true,'message' => 'Fetch Patient details.', 'data' => $patient]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    function patients_update(Request $request){
        try {
            // Validate the form inputs
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'name' => 'required|string|max:255',
                'gender' => 'required|in:male,female,others',
                'age' => 'required|numeric',
                'phone' => 'required|numeric|digits:10',
                'language_id' => 'nullable|exists:languages,id',
                'patient_id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
            $customArr = [
                'created_by' => Auth::user()->id,
                'title' => $request->title,
                'name' => $request->name,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'blood_group' => $request->blood_group,
                'language_id' => $request->language_id,
            ];
            if(!empty($request->dob)){
                $customArr['dob'] = date("Y-m-d", strtotime($request->dob));
            }
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
                $customArr['image'] = $filePath;
            }
            $patient = Patient::where('id',$request->patient_id)->update($customArr);
            $response = [
                'success' => true,
                'patient_id' => $request->patient_id,
                'message' => 'Patient information updated successfully!',
            ];
            return response()->json($response);
        } catch (\Exception $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response);
        }
    }

    public function patients_delete($id)
    {
        $quickNote = QuickNote::find($id);
        if ($quickNote) {
            $quickNote->delete();
            return response()->json(['success' => true,'message' => 'Quick Note Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
