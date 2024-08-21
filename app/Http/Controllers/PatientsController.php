<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PatientsController extends Controller
{

    public function patientView()
    {
        return view('admin.patients.patientsList');    
    }

    public function getAllPatients(Request $request)
    {
        $getPatientsData = Patients::with(['person.city','person.gender'])->where('isActive',1)->latest()->get(['id','person_ID'])->toArray();
        
        if($request->ajax()){
            return DataTables::of($getPatientsData)
                ->addIndexColumn()
                ->editColumn('name', function($row){
                    return (isset($row['person'])) ? $row['person']['full_name'] : "";
                })
                ->editColumn('email', function($row){
                    return (isset($row['person'])) ? $row['person']['email'] : "";
                })
                ->editColumn('mobile', function($row){
                    return (isset($row['person'])) ? $row['person']['mobile'] : "";
                })
                ->editColumn('address', function($row){
                    return (isset($row['person'])) ? $row['person']['address'] : "";
                })
                ->editColumn('gender', function($row){
                    return (!empty($row['person']['gender'])) ? $row['person']['gender']['gender'] : '';
                })
                ->editColumn('city', function($row){
                    return (!empty($row['person']['city'])) ? $row['person']['city']['name'] : '';
                })
                ->editColumn('edit', function($row){
                    return '<button name="edit" id="edit" class="editUserDetails mr-2" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Edit">
                    <i class="fas fa-edit"  aria-hidden="true"></i>
                    </button>';
                })
                ->editColumn('delete', function($row){
                    return '<button name="delete" id="delete" class="mr-2 deleteUser" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Delete">
                    <i class="fas fa-trash"  aria-hidden="true"></i>
                    </button>';
                })
                ->rawColumns(['name','city','gender','edit','delete'])
                ->make(true);
        }
    }
}
