<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DiagnosisController extends Controller
{
    function diagnosis(Request $request){
        $data['title'] = 'Diagnosis Remembered';
        return view('diagnosis.diagnosis',$data);
    }

    public function getdiagnosis(Request $request)
    {
        // Base query
        $query = Diagnosis::query();
        // Search functionality
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        if ($request->has('order')) {
            $columns = ['id', 'title', 'status', 'created_at'];
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

    function diagnosis_store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
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
        ];
        if(!empty($request->diagnosis_id)){
            $diagnosis = Diagnosis::where('id', $request->diagnosis_id)->update($customArr);
        }else{
            $diagnosis = Diagnosis::create($customArr);
        }
        if ($diagnosis) {
            $message = 'Diagnosis Created Successfully!';
            if(!empty($request->diagnosis_id)){
                $message = 'Diagnosis Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Diagnosis!']);
        }
    }

    public function diagnosis_change_status($id, Request $request)
    {
        $diagnosis = Diagnosis::find($id);
        if ($diagnosis) {
            $diagnosis->status = $request->status;
            $diagnosis->save();
            $message = "Diagnosis Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Diagnosis Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function diagnosis_edit($id)
    {
        $diagnosis = Diagnosis::where('id',$id)->first();
        if ($diagnosis) {
            return response()->json(['success' => true,'message' => 'Fetch Diagnosis.', 'data' => $diagnosis]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function diagnosis_delete($id)
    {
        $diagnosis = Diagnosis::find($id);
        if ($diagnosis) {
            $diagnosis->delete();
            return response()->json(['success' => true,'message' => 'Diagnosis Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
