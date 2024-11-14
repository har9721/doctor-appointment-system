<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentBooking;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointments;
use App\Models\Doctor;
use App\Models\Patients;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PatientsController extends Controller
{

    public function patientView()
    {
        return view('admin.patients.patientsList');    
    }

    public function getAllPatients(Request $request)
    {
        $getPatientsData = Patients::with(['user.city','user.gender'])->where('isActive',1)->latest()->get(['id','user_ID'])->toArray();
        
        if($request->ajax()){
            return DataTables::of($getPatientsData)
                ->addIndexColumn()
                ->editColumn('name', function($row){
                    return (isset($row['user'])) ? $row['user']['full_name'] : "";
                })
                ->editColumn('email', function($row){
                    return (isset($row['user'])) ? $row['user']['email'] : "";
                })
                ->editColumn('mobile', function($row){
                    return (isset($row['user'])) ? $row['user']['mobile'] : "";
                })
                ->editColumn('address', function($row){
                    return (isset($row['user'])) ? $row['user']['address'] : "";
                })
                ->editColumn('gender', function($row){
                    return (!empty($row['user']['gender'])) ? $row['user']['gender']['gender'] : '';
                })
                ->editColumn('city', function($row){
                    return (!empty($row['user']['city'])) ? $row['user']['city']['name'] : '';
                })
                ->editColumn('edit', function($row){
                    return '<button name="edit" id="edit" class="editUserDetails mr-2" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Edit">
                    <i class="fas fa-edit"  aria-hidden="true"></i>
                    </button>';
                })
                ->editColumn('delete', function($row){
                    return '<button name="delete" id="delete" class="mr-2 deleteUser" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Delete">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                    </button>';
                })
                ->rawColumns(['name','city','gender','edit','delete'])
                ->make(true);
        }
    }

    public function viewAppointmentBookingPage()
    {
        $getLoginPatientsId = Patients::getLoginPatientsId();

        return view('patients.appointmentBookingPage',compact('getLoginPatientsId'));    
    }

    public function searchDoctor(Request $request)
    {
        $specialty = isset($request->speciality) ? $request->speciality : null;
        $date = isset($request->date) ? date('Y-m-d',strtotime($request->date)) : null;
        $city_ID = isset($request->city) ? $request->city : null;

        $getDoctorDetails = Doctor::with(['timeSlot' => function($q) use($date){
            $q->when(!empty($date), function($query) use($date){
                $query->where('availableDate',$date)->where('isBooked',0);
            });
        }])
        ->join('mst_specialties','mst_specialties.id','doctors.specialty_ID')
        ->join('users','users.id','doctors.user_ID')
        ->join('cities','cities.id','users.city_ID')
        ->join('mst_genders','mst_genders.id','users.gender_ID')
        ->when(!empty($city_ID), function($query) use($city_ID){
            $query->where('users.city_ID',$city_ID);
        })
        ->when(!empty($specialty), function($query) use($specialty){
            $query->where('mst_specialties.id',$specialty);
        })
        ->get(['doctors.id','doctors.specialty_ID','doctors.fileName','doctors.licenseNumber','users.id as personId','users.first_name','users.last_name','users.email','users.age','users.mobile','users.address','users.gender_ID','mst_genders.gender','mst_specialties.specialtyName','mst_specialties.id as specialtyId']);

        return $getDoctorDetails;
    }

    public function bookAppointment(AppointmentBooking $request)
    {
        $data = $request->validated();

        // $validated = Validator::make($data,[
        //     'date' => ['required','date_format:d-m-Y','after_or_equal:'.date('d-m-Y')],
        //     'timeSlot' => [Rule::unique('appointments','doctorTimeSlot_ID')->where('appointmentDate',$data['date'])->where('patient_ID',$data['patient_ID'])->where('doctorTimeSlot_ID',$data['timeSlot'])]
        // ]);

        // if($validated->fails())
        // {
        //     $response['status'] = 'error';
        //     $response['message'] = $validated->messages()->first('date');
        // }else{

            $bookAppointment = Appointments::bookPatientAppointment($data);

            if($bookAppointment != null)
            {
                $response['status'] = 'success';
                $response['message'] = 'Appointment book successfully.';
            }else{
                $response['status'] = 'success';
                $response['message'] = 'Appointment not book successfully.';
            }
        // }

        return response()->json($response);
    }

    public function getAllPatientsList()
    {
        return Patients::with('user')->get();   
    }
}
