<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Bill;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            DB::raw('COUNT(bills.id) as bill_count'),
        ];
        // Base query
        $query = Patient::select($columns)
        ->leftJoin('bills', 'patients.id', '=', 'bills.patient_id')
        ->groupBy([
            'patients.id',
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
        ]);
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
                'flag',
                'bill_count'
            ];
            
            $columnIndex = $request->order[0]['column'] ?? 0; // Default to column index 0
            $sortColumn = $columns[$columnIndex] ?? 'created_at'; // Default to 'created_at'
            $sortDirection = $request->order[0]['dir'] ?? 'desc'; // Default to 'desc'
             // Explicitly use 'patients.created_at' for ordering
            $query->orderBy('patients.' . $sortColumn, $sortDirection);
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
            // Begin a database transaction
            DB::beginTransaction();
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
                'service_name' => 'required',
                'unit_price' => 'required|numeric',
                'discount' => 'required|numeric',
                'mode' => 'required',
                'doctor_id' => 'required|numeric',
                'date' => 'required',
                'time' => 'required',
                'duration' => 'required',
                'status' => 'required',
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
            $billArr = [
                'created_by' => Auth::user()->id,
                'service' => $request->service_name,
                'unit_price' => $request->unit_price,
                'discount' => $request->discount,
                'patient_id' => $patient->id,
                'invoice' => generateUnique(),
                'mode' => $request->mode,
            ];
            $bill = Bill::create($billArr);
            $date = $request->date;
            $dateObject = DateTime::createFromFormat('d/m/Y', $date);
            if ($dateObject) {
                $formatted_date = $dateObject->format('Y-m-d'); // Outputs: 2024-12-12
            }
            $appointmentArr = [
                'created_by' => Auth::user()->id,
                'service' => $request->service_name,
                'date' => $formatted_date,
                'time' => date('H:i:s', strtotime($request->time)),
                'duration' => $request->duration,
                'patient_id' => $patient->id,
                'status' => 1,
            ];
            $appointment = Appointment::create($appointmentArr);
            // Commit the transaction
            DB::commit();
            $response = [
                'success' => true,
                'message' => 'Patient information saved successfully!',
            ];
            return response()->json($response);
        } catch (\Exception $th) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response);
        }
    }

    public function patients_edit($id)
    {
        $patient = Patient::where('id',$id)->first();
        // $bills = Bill::where('patient_id', $id)->latest('created_at')->where('status',1)->get();
        if ($patient) {
            $data['patient'] = $patient;
            // $data['bills'] = $bills;
            $bills_info = view('patients.patient_details', $data)->render();
            $response = [
                'success' => true,
                'message' => 'Fetch Patient details.', 
                'data' => $patient,
                'patient_details' => $bills_info,
            ];
            return response()->json($response);
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

    public function patients_service_details($id)
    {
        $service = Service::find($id);
        if ($service) {
            return response()->json([
                'unit_price' => $service->unit_price, // Assuming `unit_price` column exists
                'discount' => $service->discount,     // Assuming `discount` column exists
                'service' => $service->service,     // Assuming `discount` column exists
            ]);
        }

        return response()->json([
            'error' => 'Service not found',
        ], 404);
    }


    public function getBills(Request $request)
    {
        // Retrieve the bills with pagination, filters, etc.
        $bills = Bill::orderby('created_at', 'desc')->paginate(10); // Adjust the number of records per page

        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $bills->total(),
            'recordsFiltered' => $bills->total(),
            'data' => $bills->items(),
        ]);
    }

    function bill_store(Request $request){
        try {
            // Validate the form inputs
            $validator = Validator::make($request->all(), [
                'service_name' => 'required',
                'unit_price' => 'required|numeric',
                'discount' => 'required|numeric',
                'patient_id' => 'required|numeric',
                'mode' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }
            $customArr = [
                'created_by' => Auth::user()->id,
                'service' => $request->service_name,
                'unit_price' => $request->unit_price,
                'discount' => $request->discount,
                'patient_id' => $request->patient_id,
                'invoice' => generateUnique(),
                'mode' => $request->mode,
            ];
            $bill = Bill::create($customArr);
            $response = [
                'success' => true,
                'message' => 'Bill created successfully!!!',
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

    public function patients_bills($id)
    {
        $patient = Patient::where('id',$id)->first();
        $bills = Bill::where('patient_id', $id)->latest('created_at')->where('status',1)->get();
        if ($patient) {
            $data['patient'] = $patient;
            $data['bills'] = $bills;
            $bills_info = view('patients.patient_bills', $data)->render();
            $response = [
                'success' => true,
                'message' => 'Fetch Patient details.', 
                'data' => $patient,
                'patient_bills' => $bills_info,
            ];
            return response()->json($response);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
