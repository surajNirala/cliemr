<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestPrescribe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TestPrescribeController extends Controller
{
    function testprescribes(Request $request){
        $data['title'] = 'Test Prescribe';
        return view('custom_templates.testprescribes.testprescribes',$data);
    }

    public function gettestprescribes(Request $request)
    {
        // Base query
        $query = TestPrescribe::query();
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
            $columns = ['id', 'title', 'description', 'used_count', 'status', 'created_at'];
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

    function testprescribes_store(Request $request){
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
        if(!empty($request->testprescribe_id)){
            $testprescribes = TestPrescribe::where('id', $request->testprescribe_id)->update($customArr);
        }else{
            $testprescribes = TestPrescribe::create($customArr);
        }
        if ($testprescribes) {
            $message = 'Test Prescribe Created Successfully!';
            if(!empty($request->testprescribe_id)){
                $message = 'Test Prescribe Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Test Prescribe!']);
        }
    }

    public function testprescribes_change_status($id, Request $request)
    {
        $testprescribes = TestPrescribe::find($id);
        if ($testprescribes) {
            $testprescribes->status = $request->status;
            $testprescribes->save();
            $message = "Test Prescribe Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Test Prescribe Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function testprescribes_edit($id)
    {
        $testprescribes = TestPrescribe::where('id',$id)->first();
        if ($testprescribes) {
            return response()->json(['success' => true,'message' => 'Fetch Test Prescribe.', 'data' => $testprescribes]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function testprescribes_delete($id)
    {
        $testprescribes = TestPrescribe::find($id);
        if ($testprescribes) {
            $testprescribes->delete();
            return response()->json(['success' => true,'message' => 'Test Prescribe Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
