<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRegistation;
use App\Http\Requests\SpeialtyRequest;
use App\Models\Doctor;
use App\Models\Mst_specialty;
use App\Models\Person;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DoctorController extends Controller
{
    public function doctorView()
    {
        return view('admin.doctor.doctor');    
    }

    public function addDoctor()
    {
        return view('admin.doctor.addDoctor');    
    }

    public function doctorRegistration(DoctorRegistation $request)
    {
        $validated = $request->validated();

        $saveDoctorInfo = Person::addPerson($validated);

        if($saveDoctorInfo != '')
        {
            $response['status'] = 'success';
            $response['message'] = 'Doctor added successfully.';
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Doctor not added successfully.';
        }

        echo json_encode($response);
    }

    public function fetchAllDoctorList(Request $request)
    {
        $getDoctorList = Doctor::with(['person.city','person.gender','specialty'])->where('isActive',1)->latest()->get(['id','person_ID','specialty_ID','licenseNumber','isActive'])->toArray();

        if($request->ajax())
        {
            return DataTables::of($getDoctorList)
            ->addIndexColumn()
            ->editColumn('fullname', function($row){
                return isset($row['person']) ? $row['person']['full_name'] : '';
            })

            ->editColumn('email', function($row){
                return isset($row['person']['email']) ? $row['person']['email'] : '';
            })
            
            ->editColumn('mobile', function($row){
                return isset($row['person']['mobile']) ? $row['person']['mobile'] : '';
            })

            ->editColumn('gender', function($row){
                return (isset($row['person']['gender'])) ? $row['person']['gender']['gender'] : '';    
            })

            ->editColumn('city', function($row){
                return (isset($row['person']['city'])) ? $row['person']['city']['name'] : null;
            })

            ->editColumn('specialty', function($row){
                return (isset($row['specialty'])) ? $row['specialty']['specialtyName'] : null;
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

            ->rawColumns(['edit','delete'])
            ->make(true);
        }
    }   

    public function getAllSpecialty()
    {
        return view('admin.doctor.specialty');  
    }

    public function saveSpecialty(SpeialtyRequest $request)
    {
        $data = $request->validated();

        $addSpecialty = Mst_specialty::addSpecialty($data);

        if($addSpecialty != '')
        {
            $response['status'] = 'success';
            $response['message'] = 'Specialty added successfully.';
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Specialty not added successfully.';
        }

        echo json_encode($response);
    }

    public function fetchAllSpecialty(Request $request)
    {
        $getAllSpecialty = Mst_specialty::getAllSpecialty();

        if($request->ajax())
        {
            return DataTables::of($getAllSpecialty)
                ->addIndexColumn()
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
                ->rawColumns(['edit','delete'])
                ->make(true);
        }    
    }

    public function fetchSpecialtyList()
    {
        return Mst_specialty::getAllSpecialty();
    }
}
