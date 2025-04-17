<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRegistration;
use App\Http\Requests\SpecialtyRequest;
use App\Http\Requests\SpeialtyRequest;
use App\Http\Requests\ValidateTimeSlot;
use App\Jobs\SendDoctorMail;
use App\Models\city;
use App\Models\Doctor;
use App\Models\DoctorTimeSlots;
use App\Models\Mst_specialty;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        if(!empty($validated['profile_image']))
        {
            $file = $request->file('profile_image');
            $fileName = time().'_'.$file->getClientOriginalName();

            // add file name in validated array to save in a DB
            $validated['fileName'] = $fileName;

            // store file in storage folder
            Storage::disk('public')->putFileAs('doctorProfilePictures',$file,$fileName);
        }

        $saveDoctorInfo = User::addUser($validated);

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
        $getDoctorList = Doctor::with([
            'user.city',
            'user.gender',
            'specialty'
        ])
        ->withCount(['timeSlot' => function($query) {
            $query->where('availableDate',date('Y-m-d'))
            ->where('isBooked',0)
            ->where('isDeleted',0);
        }])
        ->where('isActive',1)
        ->latest()
        ->get(['id','user_ID','specialty_ID','licenseNumber','experience','isActive'])
        ->toArray();

        if($request->ajax())
        {
            return DataTables::of($getDoctorList)
            ->addIndexColumn()
            ->editColumn('fullname', function($row){
                return isset($row['user']) ? $row['user']['full_name'] : '';
            })

            ->editColumn('email', function($row){
                return isset($row['user']['email']) ? $row['user']['email'] : '';
            })
            
            ->editColumn('mobile', function($row){
                return isset($row['user']['mobile']) ? $row['user']['mobile'] : '';
            })

            ->editColumn('age', function($row){
                return isset($row['user']['age']) ? $row['user']['age'] : '';
            })

            ->editColumn('gender', function($row){
                return (isset($row['user']['gender'])) ? $row['user']['gender']['gender'] : '';    
            })

            ->editColumn('city', function($row){
                return (isset($row['user']['city'])) ? $row['user']['city']['name'] : null;
            })

            ->editColumn('specialty', function($row){
                return (isset($row['specialty'])) ? $row['specialty']['specialtyName'] : null;
            })

            ->editColumn('action', function($row){
                $edit = '<div class="d-flex justify-space-between"><a href="'.route('admin.editDoctorDetails',['doctor' => $row['id']]).'">
                <button name="edit" id="edit" class="editDoctorDetails mr-2" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Edit">
                <i class="fas fa-edit"  aria-hidden="true"></i>
                </button></a>
                
                <button name="delete" id="delete" class="mr-2 deleteDoctor" data-toggle="tooltip" data-id = "'.$row['id'].'" data-user_ID="'.$row['user_ID'].'" data-placement="bottom" title="Delete">
                <i class="fas fa-trash"  aria-hidden="true"></i>
                </button>';

                $action = (isset($row['time_slot_count']) && $row['time_slot_count'] == 0) ? '' : '</div>';

                $sendMail = (isset($row['time_slot_count']) && $row['time_slot_count'] == 0) ? 
                '<button name="send_mail" id="send_mail" class="mr-2 sendMail btn btn-sm border text-white bg-dark" data-toggle="tooltip" data-id = "'.$row['id'].'" data-placement="bottom" title="Send Time Slot Mail">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                </button></div>' : '';

                return $edit.$action.$sendMail;
            })
            ->editColumn('timeSlot', function($row){
                return (isset($row['time_slot_count'])) ? 
                    ($row['time_slot_count'] != 0 ?
                        '<label class="badge bg-warning text-white">'.ucfirst('Yes').'</label><br/>['. $row['time_slot_count'].']' : 
                        '<label class="badge bg-danger text-white">'.ucfirst('No').'</label>'
                    )
                : 0;
            })
            ->rawColumns(['action','timeSlot'])
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
            $response['message'] = 'Time slot is moved successfully.';
        }else
        {
            $response['status'] = 'success';
            $response['message'] = 'Time slot is not moved successfully.';
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

    public function editDoctorForm(Doctor $doctor)
    {
        $getDoctorDetails = Doctor::with(['user'])->where('id',$doctor->id)
        ->get(['id','fileName','experience','user_ID','specialty_ID','licenseNumber'])
        ->map(function($doctor){
            $doctor['first_name'] = $doctor->user->first_name;
            $doctor['last_name'] = $doctor->user->last_name;
            $doctor['email'] = $doctor->user->email;
            $doctor['mobile'] = $doctor->user->mobile;
            $doctor['age'] = $doctor->user->age;
            $doctor['city_ID'] = $doctor->user->city_ID;
            $doctor['gender_ID'] = $doctor->user->gender_ID;
            $doctor['state_ID'] = city::where('id',$doctor->user->city_ID)->pluck('state_id')->first();

            return $doctor;
        })->toArray();

        $doctorDetails = (!empty($getDoctorDetails)) ? $getDoctorDetails[0] : null;

        $heading = (Auth::user()->role->roleName === 'Admin') ? 'Edit Doctor Details' : (Auth::user()->role->roleName === 'Doctor' ? 'My Profile' : '');

        $backUrl = (Auth::user()->role->roleName === 'Admin') ? 'admin.doctor' : (Auth::user()->role->roleName === 'Doctor' ? 'home' : '');

        return view('admin.doctor.editDoctor',compact('doctorDetails','heading','backUrl'));
    }

    public function doctorUpdate(DoctorRegistration $request)
    {
        $validated = $request->validated();

        if(isset($validated['profile_image']) && $validated['imageUpdateOption'] == 'Yes')
        {
            ini_set('upload_max_filesize', -1); 
            ini_set('post_max_size', -1); 

            $file = $request->file('profile_image');
            $fileName = time().'_'.$file->getClientOriginalName();

            // add file name in validated array to save in a DB
            $validated['fileName'] = $fileName;

            // store file in storage folderx
            Storage::disk('public')->putFileAs('doctorProfilePictures',$file,$fileName);
        }

        $updateUserDetails = User::updateUserInfo($validated);

        $url = (Auth::user()->role_ID == 1) ? 'doctor-list' : '';

        if($updateUserDetails != '')
        {
            $response['status'] = 'success';
            $response['url'] = $url;
            $response['message'] = 'Doctor details is updated successfully.';
        }else
        {
            $response['status'] = 'error';
            $response['url'] = $url;
            $response['message'] = 'Doctor details is not updated successfully.';
        }

        echo json_encode($response);
    }

    public function deleteDoctor(Request $request)
    {
        
        $deleteDoctor = User::deleteUser($request->all());

        if($deleteDoctor != '')
        {
            $response['status'] = 'success';
            $response['message'] = 'Doctor deleted successfully.';
        }else
        {
            $response['status'] = 'success';
            $response['message'] = 'Doctor not deleted successfully.';
        }

        echo json_encode($response);
    }

    public function getAllDoctorList(Request $request)
    {
        return Doctor::where('specialty_ID',$request->speciality_id)->with('user')->get();    
    }

    public function fetchTimeSlotForDate(Request $request)
    {
        if($request->ajax())
        {
            $selected_date = date('Y-m-d', strtotime($request->date));

            $getAvailableTimeSlot = Doctor::with(['timeSlot' => function($query) use($selected_date){
                $query->where('availableDate',$selected_date)->where('isBooked',0);
            }])->where('id',$request->doctor_ID)
            ->where('isActive',1)
            ->first(['id','specialty_ID','user_ID']);

            return $getAvailableTimeSlot;
        }else{
            return null;
        }
    }

    public function getEditForm()
    {
        $getDoctorId = Doctor::where('user_ID',Auth::user()->id)->first('id');

        $this->editDoctorForm($getDoctorId->id);
    }

    public function getDoctorListForEdit(Request $request)
    {
        $date = ($request->date != '') ? date('Y-m-d',strtotime($request->date)) : '';

        $getDoctorDetails = Doctor::when(
            $request->doctor_ID, function($query) use($request) {
                $query->where('id',$request->doctor_ID);
            })
            ->when($request->speciality_ID, function($query) use($request) {
                $query->where('specialty_ID',$request->speciality_ID);
            })
            ->when($request->city_id, function($query) use($request) {
                $query->whereHas('user.city', function ($q) use($request) {
                    $q->where('id',$request->city_id);
                });   
            })
            ->when($request->date, function($query) use($date) {
                $query->whereHas('timeSlot', function($q) use($date) {
                    $q->where('availableDate', $date);
                });
            })
            ->with(['user.city','timeSlot' => function($query) use($date) {
                $query->where('availableDate', $date)
                // ->where('isBooked',0)
                ->orderBy('start_time','asc');
            }])
            ->first(); 

        return response()->json($getDoctorDetails);
    }

    public function sendTimeSlotMail(Doctor $doctor)
    {
        try {
            if(!empty($doctor->user_ID))
            {
                $getDoctorDetails = User::getUserInfo($doctor->user_ID);

                dispatch(new SendDoctorMail($getDoctorDetails->toArray(),'controller'));

                return response()
                    ->json([
                        'status' => 'success',
                        'message' => 'Mail sent successfully.'
                    ]);
            }
        } catch (\Throwable $th) {
            return response()
                ->json([
                    'status' => 'error',
                    'message' => $th->getMessage()
                ]);
        }
    }
}
