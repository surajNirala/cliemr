<?php

namespace App\Http\Controllers;

use App\Models\QuickNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuickNoteController extends Controller
{
    function quicknotes(Request $request){
        $data['title'] = 'Quick Note';
        return view('custom_templates.quicknotes.quick-notes',$data);
    }

    public function getquicknotes(Request $request)
    {
        // Base query
        $query = QuickNote::query();
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

    function quicknotes_store(Request $request){
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

    public function quicknotes_change_status($id, Request $request)
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

    public function quicknotes_edit($id)
    {
        $quickNote = QuickNote::where('id',$id)->first();
        if ($quickNote) {
            return response()->json(['success' => true,'message' => 'Fetch Quick Note.', 'data' => $quickNote]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function quicknotes_delete($id)
    {
        $quickNote = QuickNote::find($id);
        if ($quickNote) {
            $quickNote->delete();
            return response()->json(['success' => true,'message' => 'Quick Note Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
