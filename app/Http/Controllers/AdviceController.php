<?php

namespace App\Http\Controllers;

use App\Models\Advice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdviceController extends Controller
{
    function advice(Request $request){
        $data['title'] = 'Advice';
        // $data['advice'] = Advice::get();
        return view('custom_templates.advice.advice',$data);
    }

    public function getadvice(Request $request)
    {
        // Base query
        $query = Advice::query();
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

    function advice_store(Request $request){
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
        if(!empty($request->advice_id)){
            $advice = Advice::where('id', $request->advice_id)->update($customArr);
        }else{
            $advice = Advice::create($customArr);
        }
        if ($advice) {
            $message = 'Advice Created Successfully!';
            if(!empty($request->advice_id)){
                $message = 'Advice Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Advice!']);
        }
    }

    public function advice_edit($id)
    {
        $advice = Advice::where('id',$id)->first();
        if ($advice) {
            return response()->json(['success' => true,'message' => 'Fetch Advice.', 'data' => $advice]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function advice_change_status($id, Request $request)
    {
        $advice = Advice::find($id);
        if ($advice) {
            $advice->status = $request->status;
            $advice->save();
            $message = "Advice Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Advice Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function advice_delete($id)
    {
        $advice = Advice::find($id);
        if ($advice) {
            $advice->delete();
            return response()->json(['success' => true,'message' => 'Advice Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
