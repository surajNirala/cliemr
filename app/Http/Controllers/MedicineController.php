<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    function medicines(Request $request)
    {
        $data['title'] = 'Medicine';
        return view('medicine_management.medicines',$data);
    }

    function getmedicines(Request $request)
    {
        try {
            // Base query
            $columns = [
                'medicines.id as medicine_id','medicines.name as medicine_name',
                'medicines.dosage1','medicines.dosage2','medicines.dosage3',
                'medicines.unit','medicines.time','medicines.where',
                'medicines.generic_name','medicines.frequency','medicines.duration',
                'medicines.quantity','medicines.notes','medicines.status',
                'medicines.created_at as created_at',
                'medicine_types.name as medicine_type_name',
                'medicine_administrations.name as medicine_administration_name',
                'users.id as user_id','users.name as person_name',
            ];
            $query = Medicine::select($columns)
                        ->leftJoin('medicine_administrations', 'medicine_administrations.id', '=', 'medicines.medicine_administration_id')
                        ->leftJoin('medicine_types', 'medicine_types.id', '=', 'medicines.medicine_type_id')
                        ->leftJoin('users', 'users.id', '=', 'medicines.user_id');
                        // ->groupBy(['medicines.id']);
            // Search functionality
            if ($request->has('search') && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('medicines.name', 'like', "%{$search}%")
                    ->orWhere('medicines.generic_name', 'like', "%{$search}%")
                    ->orWhere('medicines.notes', 'like', "%{$search}%")
                    ->orWhere('medicine_types.name', 'like', "%{$search}%")
                    ->orWhere('medicine_administrations.name', 'like', "%{$search}%");
                });
            }

            // Sorting functionality
            if ($request->has('order')) {
                $columns = [
                    'medicine_id','medicine_name',
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
}
