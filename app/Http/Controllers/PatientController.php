<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

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
            'patients.title',
            'patients.name',
            'patients.gender',
            'patients.age',
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
            'patients.created_at'
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
                'title',
                'name',
                'gender',
                'age',
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
                'created_at'
            ];
            $columnIndex = $request->order[0]['column']; // Column index
            $sortColumn = $columns[$columnIndex]; // Column name
            $sortDirection = $request->order[0]['dir']; // Sort direction (asc/desc)
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
    }

    function patients_store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }
        $customArr = [
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'description' => $request->description,
        ];
        if(!empty($request->quick_note_id)){
            $quickNote = QuickNote::where('id', $request->quick_note_id)->update($customArr);
        }else{
            $quickNote = QuickNote::create($customArr);
        }
        if ($quickNote) {
            $message = 'Quick Note Created Successfully!';
            if(!empty($request->quick_note_id)){
                $message = 'Quick Note Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Quick note!']);
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
        $quickNote = QuickNote::where('id',$id)->first();
        if ($quickNote) {
            return response()->json(['success' => true,'message' => 'Fetch Quick Note.', 'data' => $quickNote]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
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
