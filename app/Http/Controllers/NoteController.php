<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    function notes(Request $request){
        $data['title'] = 'Note Remembered';
        return view('notes.notes',$data);
    }

    public function getnotes(Request $request)
    {
        // Base query
        $query = Note::query();
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

    function notes_store(Request $request){
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
        if(!empty($request->note_id)){
            $note = Note::where('id', $request->note_id)->update($customArr);
        }else{
            $note = Note::create($customArr);
        }
        if ($note) {
            $message = 'Note Created Successfully!';
            if(!empty($request->note_id)){
                $message = 'Note Updated Successfully!';
            }
            return response()->json(['success' => true, 'message' => $message]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to notes!']);
        }
    }

    public function notes_change_status($id, Request $request)
    {
        $note = Note::find($id);
        if ($note) {
            $note->status = $request->status;
            $note->save();
            $message = "Note Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Note Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function notes_edit($id)
    {
        $note = Note::where('id',$id)->first();
        if ($note) {
            return response()->json(['success' => true,'message' => 'Fetch notes.', 'data' => $note]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function notes_delete($id)
    {
        $note = Note::find($id);
        if ($note) {
            $note->delete();
            return response()->json(['success' => true,'message' => 'Note Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

}
