<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    function complaints(Request $request){
        $data['title'] = 'Complaint Remembered';
        return view('complaints.complaints',$data);
    }

    public function getcomplaints(Request $request)
    {
        // Base query
        $query = Complaint::query();
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

    function complaints_store(Request $request){
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
        if(!empty($request->complaint_id)){
            $complaint = Complaint::where('id', $request->complaint_id)->update($customArr);
        }else{
            $complaint = Complaint::create($customArr);
        }
        if ($complaint) {
            $message = 'Complaint Created Successfully!';
            if(!empty($request->complaint_id)){
                $message = 'Complaint Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Complaint!']);
        }
    }

    public function complaints_change_status($id, Request $request)
    {
        $complaint = Complaint::find($id);
        if ($complaint) {
            $complaint->status = $request->status;
            $complaint->save();
            $message = "Complaint Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Complaint Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function complaints_edit($id)
    {
        $complaint = Complaint::where('id',$id)->first();
        if ($complaint) {
            return response()->json(['success' => true,'message' => 'Fetch Complaint.', 'data' => $complaint]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function complaints_delete($id)
    {
        $complaint = Complaint::find($id);
        if ($complaint) {
            $complaint->delete();
            return response()->json(['success' => true,'message' => 'Complaint Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
