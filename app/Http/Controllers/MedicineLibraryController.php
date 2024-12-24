<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Models\MedicineLibrary;
use Illuminate\Support\Facades\Validator;

class MedicineLibraryController extends Controller
{
    function medicinelibraries(Request $request)
    {
        $data['title'] = 'Medicine Library';
        return view('medicine_management.medicinelibrary',$data);
    }

    function getmedicinelibraries(Request $request)
    {
        try {
            // Base query
            $columns = [
                'medicine_libraries.id as medicinelibrary_id','medicines.title as medicine_name',
                'medicine_libraries.dosage1','medicine_libraries.dosage2','medicine_libraries.dosage3',
                'medicine_libraries.unit','medicine_libraries.time','medicine_libraries.where',
                'medicine_libraries.generic_name','medicine_libraries.frequency','medicine_libraries.duration',
                'medicine_libraries.quantity','medicine_libraries.notes','medicine_libraries.status',
                'medicine_libraries.created_at as created_at',
                'medicine_types.name as medicine_type_name',
                'medicine_administrations.name as medicine_administration_name',
                'users.id as user_id','users.name as person_name',
            ];
            $query = MedicineLibrary::select($columns)
                        ->leftJoin('medicines', 'medicines.id', '=', 'medicine_libraries.medicine_id')
                        ->leftJoin('medicine_administrations', 'medicine_administrations.id', '=', 'medicine_libraries.medicine_administration_id')
                        ->leftJoin('medicine_types', 'medicine_types.id', '=', 'medicine_libraries.medicine_type_id')
                        ->leftJoin('users', 'users.id', '=', 'medicine_libraries.user_id');
                        // ->groupBy(['medicine_libraries.id']);
            // Search functionality
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('medicine_libraries.name', 'like', "%{$search}%")
                    ->orWhere('medicine_libraries.generic_name', 'like', "%{$search}%")
                    ->orWhere('medicine_libraries.notes', 'like', "%{$search}%")
                    ->orWhere('medicine_types.name', 'like', "%{$search}%")
                    ->orWhere('medicine_administrations.name', 'like', "%{$search}%");
                });
            }

            // Sorting functionality
            if ($request->has('order')) {
                $columns = [
                    'medicinelibrary_id','medicine_name',
                    'dosage1','dosage2','dosage3',
                    'unit','time','where',
                    'generic_name','frequency','duration',
                    'quanity','notes','status',
                    'created_at',
                    'medicine_type_name',
                    'medicine_administration_name',
                    'user_id','person_name',
                ];
                $columnIndex = $request->order[0]['column']; // Column index
                $sortColumn = $columns[$columnIndex] ?? 'created_at'; // Default to 'created_at'
                $sortDirection = $request->order[0]['dir'] ?? 'desc'; // Default to 'desc'
                $query->orderBy($sortColumn, $sortDirection);
            }else {
                // Default order by 'created_at' in descending order
                // $query->groupBy(['medicines.id']);
                $query->orderBy('created_at', 'desc');
            }

            // Pagination
            // $query->groupBy(['medicines.id']);
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $totalRecords = $query->count(); // Total records without filtering
            $data = $query->skip($start)->take($length)->get(); // Paginated data
            // print_r($data);
            // die;
            // DataTables JSON response
            return response()->json([
                "draw" => $request->draw, // Draw counter
                "recordsTotal" => $totalRecords, // Total records
                "recordsFiltered" => $totalRecords, // Total records after filtering
                "data" => $data, // Data
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    function medicinelibraries_store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'medicine_type_id' => 'required|exists:medicine_types,id',
                'medicine_id' => 'required|exists:medicines,id',
                'dosage1' => 'required|numeric',
                'dosage2' => 'required|numeric',
                'dosage3' => 'required|numeric',
                'medicine_administration_id' => 'required|exists:medicine_administrations,id',
                'unit' => 'required|string|max:255',
                'time' => 'required|string|max:255',
                'where' => 'required|string|max:255',
                'generic_name' => 'required|string|max:255',
                'frequency' => 'required|string|max:255',
                'duration' => 'required|string|max:255',
                'quantity' => 'required|numeric',
                'notes' => 'nullable|string|max:1000',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422); // 422 Unprocessable Entity
            }

            $customArr = [
                'medicine_type_id' => $request->medicine_type_id,
                'medicine_id' => $request->medicine_id,
                'dosage1' => $request->dosage1,
                'dosage2' => $request->dosage2,
                'dosage3' => $request->dosage3,
                'medicine_administration_id' => $request->medicine_administration_id,
                'unit' => $request->unit,
                'time' => $request->time,
                'where' => $request->where,
                'generic_name' => $request->generic_name,
                'frequency' => $request->frequency,
                'duration' => $request->duration,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ];
            $medicineLibrary = MedicineLibrary::create($customArr);
            if ($medicineLibrary) {
                return response()->json(['success' => true, 'message' => 'Medicine Library Created Successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to medicine library!']);
            }
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function medicines_edit($id)
    {
        try {
            $columns = [
                'medicine_libraries.id as medicinelibrary_id',
                'medicine_libraries.dosage1','medicine_libraries.dosage2','medicine_libraries.dosage3',
                'medicine_libraries.unit','medicine_libraries.time','medicine_libraries.where',
                'medicine_libraries.generic_name','medicine_libraries.frequency','medicine_libraries.duration',
                'medicine_libraries.quantity','medicine_libraries.notes','medicine_libraries.status',
                'medicine_libraries.created_at as created_at',
                'medicines.title as medicine_name','medicines.id as medicine_id',
                'medicine_types.name as medicine_type_name',
                'medicine_types.id as medicine_type_id',
                'medicine_administrations.name as medicine_administration_name',
                'medicine_administrations.id as medicine_administration_id',
                'users.id as user_id','users.name as person_name',
            ];
            $medicineLibrary = MedicineLibrary::select($columns)
                        ->leftJoin('medicines', 'medicines.id', '=', 'medicine_libraries.medicine_id')
                        ->leftJoin('medicine_administrations', 'medicine_administrations.id', '=', 'medicine_libraries.medicine_administration_id')
                        ->leftJoin('medicine_types', 'medicine_types.id', '=', 'medicine_libraries.medicine_type_id')
                        ->leftJoin('users', 'users.id', '=', 'medicine_libraries.user_id')
                        ->where('medicine_libraries.id', $id)
                        ->first();

            if ($medicineLibrary) {
                $response = [
                    'success' => true, 
                    'data'   => $medicineLibrary, 
                    'getAllActiveMedicineType' => getAllActiveMedicineType(),
                    'getAllActiveMedicines' => getAllActiveMedicines(),
                    'getAllActiveMedicineAdministration' => getAllActiveMedicineAdministration(),
                    'message'=> 'Medicine Library details.'
                ];
                return response()->json($response);
            }
            $response = [
                'success' => false, 
                'message' => 'Medicine Library not found'
            ];
            return response()->json($response);
    
        } catch (\Exception $e) {
            $response = [
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ];
            return response()->json($response);
        }
    }

    function medicinelibraries_update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'medicinelibrary_id' => 'required',
                'medicine_type_id' => 'required|exists:medicine_types,id',
                'medicine_id' => 'required|exists:medicines,id',
                'dosage1' => 'required|numeric',
                'dosage2' => 'required|numeric',
                'dosage3' => 'required|numeric',
                'medicine_administration_id' => 'required|exists:medicine_administrations,id',
                'unit' => 'required|string|max:255',
                'time' => 'required|string|max:255',
                'where' => 'required|string|max:255',
                'generic_name' => 'required|string|max:255',
                'frequency' => 'required|string|max:255',
                'duration' => 'required|string|max:255',
                'quantity' => 'required|numeric',
                'notes' => 'nullable|string|max:1000',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422); // 422 Unprocessable Entity
            }

            $customArr = [
                'medicine_type_id' => $request->medicine_type_id,
                'medicine_id' => $request->medicine_id,
                'dosage1' => $request->dosage1,
                'dosage2' => $request->dosage2,
                'dosage3' => $request->dosage3,
                'medicine_administration_id' => $request->medicine_administration_id,
                'unit' => $request->unit,
                'time' => $request->time,
                'where' => $request->where,
                'generic_name' => $request->generic_name,
                'frequency' => $request->frequency,
                'duration' => $request->duration,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ];
            $medicineLibrary = MedicineLibrary::where('id', $request->medicinelibrary_id)
                                              ->update($customArr);
            if ($medicineLibrary) {
                return response()->json(['success' => true, 'message' => 'Medicine Library Updated Successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to medicine library!']);
            }
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function medicinelibraries_change_status($id, Request $request)
    {
        $medicineLibrary = MedicineLibrary::find($id);
        if ($medicineLibrary) {
            $medicineLibrary->status = $request->status;
            $medicineLibrary->save();
            $message = "Medicine Library Status Inactive Successfully.";
            if($request->status == 1){
                $message = "Medicine Library Status Active Successfully.";
            }
            return response()->json(['success' => true,'message' => $message]);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }

    public function medicinelibraries_delete($id)
    {
        $MedicineLibrary = MedicineLibrary::find($id);
        if ($MedicineLibrary) {
            $MedicineLibrary->delete();
            return response()->json(['success' => true,'message' => 'Medicine Library Deleted Successfully.']);
        }
        return response()->json(['success' => false,'message' => 'Internal Server error'], 400);
    }
}
