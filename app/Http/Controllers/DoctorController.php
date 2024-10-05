<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRegistation;
use App\Http\Requests\DoctorRegistration;
use App\Http\Requests\SpecialtyRequest;
use App\Http\Requests\SpeialtyRequest;
use App\Http\Requests\ValidateTimeSlot;
use App\Models\Doctor;
use App\Models\DoctorTimeSlots;
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

    public function doctorRegistration(DoctorRegistration $request)
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

    public function saveSpecialty(SpecialtyRequest $request)
    {
        $data = $request->validated();

        $addSpecialty = Mst_specialty::addSpecialty($data);

        $status = (empty($data['hidden_id'])) ? 'added' : 'updated';

        if($addSpecialty != '')
        {
            $response['status'] = 'success';
            $response['message'] = "Specialty $status successfully.";
        }else{
            $response['status'] = 'error';
            $response['message'] = "Specialty not $status successfully.";
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
                    return '<button name="edit" id="edit" class="editSpecialty mr-2" data-toggle="tooltip" data-id = "'.$row['id'].'" data-specialty = "'.$row['specialtyName'].'" data-placement="bottom" title="Edit">
                    <i class="fas fa-edit"  aria-hidden="true"></i>
                    </button>';
                })
                ->editColumn('delete', function($row){
                    return '<button name="delete" id="delete" class="mr-2 deleteSpecialty" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Delete">
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

    public function viewTimeSlot()
    {
        $loginUserId = Doctor::getLoginDoctorID();

        return view('doctor.viewTimeSlot',compact('loginUserId'));    
    }

    public function getTimeSlot()
    {
        $fetchAllTimeSlots = DoctorTimeSlots::fetchDoctorTimeSlots();

        return response()->json($fetchAllTimeSlots);
    }

    public function addTimeSlot(ValidateTimeSlot $request)
    {
        $addTimeSlot = DoctorTimeSlots::addDoctorTimeSlot($request->all());

        $msg = ($request['isEdit'] == '1') ? 'updated' : 'added';

        if($addTimeSlot != '')
        {
            $response['status'] = 'success';
            $response['message'] = 'Time slot is '. $msg .' successfully.';
        }else
        {
            $response['status'] = 'success';
            $response['message'] = 'Time slot is not '.$msg.' successfully.';
        }

        return response()->json($response);
    }
    
    public function deleteTimeSlot(Request $request)
    {
        $id = (isset($request->id)) ? $request->id : null;

        if($id)
        {
            $deleteTimeSlot = DoctorTimeSlots::deleteTimeSlot($id);
        }else{
            $deleteTimeSlot = '';
        }

        if($deleteTimeSlot != '')
        {
            $response['status'] = 'success';
            $response['message'] = 'Time slot is deleted successfully.';
        }else
        {
            $response['status'] = 'success';
            $response['message'] = 'Time slot is not deleted successfully.';
        }

        return response()->json($response);
    }

    public function updateTimeSlot(ValidateTimeSlot $request)
    {
        $updateTimeSlot = DoctorTimeSlots::updateDoctorTimeSlot($request->all());

        if($updateTimeSlot != '')
        {
            $response['status'] = 'success';
            $response['message'] = 'Time slot is updated successfully.';
        }else
        {
            $response['status'] = 'success';
            $response['message'] = 'Time slot is not updated successfully.';
        }

        return response()->json($response);
    }

    public function deleteSpecialty(Request $request)
    {
        $id = $request->id;
        
        if($id)
        {
            $deleteSpecialty = Mst_specialty::deleteSpecialty($id);

            if($deleteSpecialty != '')
            {
                $response['status'] = 'success';
                $response['message'] = 'Specialty is deleted successfully.';
            }else
            {
                $response['status'] = 'success';
                $response['message'] = 'Specialty is not deleted successfully.';
            }
        }else{
            $response['status'] = 'success';
            $response['message'] = 'Something went wrong.';
        }

        return response()->json($response);
    }
}
