<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentBooking;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Appointments;
use App\Models\Doctor;
use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PatientsController extends Controller
{
    public $appointment_model;

    public function __construct()
    {
        $this->appointment_model = new Appointments();
    }

    public function patientView()
    {
        return view('admin.patients.patientsList');    
    }

    public function getAllPatients(Request $request)
    {
        // $getPatientsData = Patients::with(['user.city','user.gender'])->where('isActive',1)->latest()->get(['id','user_ID'])->toArray();
        $getPatientsData = Patients::getPatientsList();
        
        if($request->ajax()){
            return DataTables::of($getPatientsData)
                ->addIndexColumn()
                ->editColumn('name', function($row){
                    return (isset($row)) ? $row['patient_full_name'] : "";
                })
                ->editColumn('email', function($row){
                    return (isset($row)) ? $row['email'] : "";
                })
                ->editColumn('mobile', function($row){
                    return (isset($row)) ? $row['mobile'] : "";
                })
                ->editColumn('age', function($row){
                    return (isset($row)) ? $row['age'] : "";
                })
                ->editColumn('address', function($row){
                    return (isset($row)) ? $row['address'] : "";
                })
                ->editColumn('gender', function($row){
                    return (!empty($row)) ? $row['gender'] : '';
                })
                ->editColumn('city', function($row){
                    return (!empty($row)) ? $row['city'] : '';
                })
                ->editColumn('action', function($row){
                    $edit = (Auth::user()->role_ID !== config('constant.doctor_role_ID')) ? '<div class="d-flex justify-space-between"><a href="'. route('admin.edit-patients',['patients' => $row['id']]) .'"><button name="edit" id="edit" class="editPatientsDetails mr-2" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Edit">
                    <i class="fas fa-edit"  aria-hidden="true"></i>
                    </button></a>' : '';

                    $delete = (Auth::user()->role_ID !== config('constant.doctor_role_ID')) ? '<button name="delete" id="delete" class="deleteUser" data-toggle="tooltip" data-id = "'.$row['id'].'"  data-user = "'. $row['user_ID'] .'" data-role = "'. $row['role_ID'] .'" data-placement="bottom" title="Delete">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                    </button></div>' : '';

                    $view = (Auth::user()->role_ID === config('constant.doctor_role_ID')) ? '<a href="'. route('admin.view-patient-history',['patients' => $row['id']]) .'">
                    <button name="view" id="view" class="viewPatientsDetails mr-2" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="View Patients Details">
                        <i class="fas fa-eye"  aria-hidden="true"></i>
                    </button></a>' : '';

                    return $edit.$delete.$view;
                })
                ->rawColumns(['name','city','gender','age','action'])
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

        $getDoctorDetails = Doctor::whereHas('timeSlot', function($q) use($date){
            $q->when(!empty($date), function($query) use($date){
                $query->where('availableDate',$date)->where('isBooked',0);
            });
        })
        ->with(['timeSlot' => function($q) use($date){
            $q->when(!empty($date), function($query) use($date){
                $query->where('availableDate',$date)
                ->where('isBooked',0)
                ->orderBy('start_time','asc');
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

        $checkForOutstandingPayments = Appointments::checkForOutstandingPayments($data['patient_ID']);

        if(empty($checkForOutstandingPayments))
        {
            $bookAppointment = $this->appointment_model->bookPatientAppointment($data);
    
            if($bookAppointment != null)
            {
                $response['status'] = 'success';
                $response['message'] = 'Appointment book successfully.';
            }else{
                $response['status'] = 'error';
                $response['message'] = 'Appointment not book successfully.';
            }
        }else{
            $response['status'] = 'error';
            $response['message'] = 'Outstanding payments found. Please complete the payments to proceed.';
        }

        return response()->json($response);
    }

    public function getAllPatientsList()
    {
        return Patients::with('user')->get(['id','user_ID']);   
    }

    public function editPatient(Patients $patients)
    {
        if(Auth::user()->role->roleName === 'Admin')
        {
            $patientsData = $patients->load([
                'user.city'
            ]);
        }else{
            $patientsData = $patients->load([
                'emergencyContact',
                'lifeStyleInformation',
                'medicalHistory',
                'user.city'
            ]);
        }

        $heading = (Auth::user()->role->roleName === 'Admin') ? 'Edit Patient Details' : (Auth::user()->role->roleName === 'Patients' ? 'My Profile' : '');

        $backUrl = (Auth::user()->role->roleName === 'Admin') ? 'admin.patients' : (Auth::user()->role->roleName === 'Petients' ? 'home' : 'home');

        $isHideSaveButton = 0;

        $class = '';

        return view('admin.patients.editPatients',compact('patientsData','heading','backUrl','isHideSaveButton','class'));
    }
    
    public function updatePatientsDetails(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        $updateUserDetails = User::updateUserInfo($validated);

        $url = (Auth::user()->role_ID == 1) ? 'patients' : '';

        $msg = (Auth::user()->role_ID == 1) ? 'Patients' : 'Profile';

        if($updateUserDetails != '')
        {
            $response['status'] = 'success';
            $response['url'] = $url;
            $response['message'] = "$msg details is updated successfully.";
        }else
        {
            $response['status'] = 'error';
            $response['url'] = $url;
            $response['message'] = "$msg details is not updated successfully.";
        }

        echo json_encode($response);
    }

    public function viewPatientsHistory(Patients $patients)
    {
        $patientsData = $patients->load(['user','emergencyContact','lifeStyleInformation','medicalHistory']);

        $backUrl = (Auth::user()->role->roleName === 'Admin') ? 'admin.patients' : (Auth::user()->role->roleName === 'Petients' ? 'home' : 'home');

        $isHideSaveButton =  1;

        $class = "readonly";

        return view('admin.viewPatientsHistory',compact('patientsData','backUrl','isHideSaveButton','class'));
    }

    public function deletePatients(Request $request)
    {
        $deletePatients = User::deleteUser($request->all());

        if($deletePatients != '')
        {
            $response['status'] = 'success';
            $response['message'] = 'Patient deleted successfully.';
        }else
        {
            $response['status'] = 'success';
            $response['message'] = 'Patient not deleted successfully.';
        }

        echo json_encode($response);
    }

    public function fetchPatientList(Request $request)
    {
        return Patients::getPatientNames();
    }
}
