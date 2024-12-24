<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{
    function medicines(Request $request){
        $data['title'] = 'Medicine';
        return view('medicine_management.medicines',$data);
    }

    public function getmedicines(Request $request)
    {
        // Base query
        $query = Medicine::query();
        // Search functionality
        if ($request->has('search') && $request->search['value'] != '') {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        if ($request->has('order')) {
            $columns = ['id', 'title', 'description', 'status', 'created_at'];
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

    function medicines_store(Request $request){
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
            $medicine = Medicine::where('id', $request->quick_note_id)->update($customArr);
        }else{
            $medicine = Medicine::create($customArr);
        }
        if ($medicine) {
            $message = 'Medicine Created Successfully!';
            if(!empty($request->quick_note_id)){
                $message = 'Medicine Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Medicine!']);
        }
    }

    public function medicines_change_status($id, Request $request)
    {
        $medicine = Medicine::find($id);
        if ($medicine) {
            $medicine->status = $request->status;
            $medicine->save();
            $message = "Medicine Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Medicine Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function medicines_edit($id)
    {
        $medicine = Medicine::where('id',$id)->first();
        if ($medicine) {
            return response()->json(['success' => true,'message' => 'Fetch Medicine.', 'data' => $medicine]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function medicines_delete($id)
    {
        $medicine = Medicine::find($id);
        if ($medicine) {
            $medicine->delete();
            return response()->json(['success' => true,'message' => 'Medicine Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
