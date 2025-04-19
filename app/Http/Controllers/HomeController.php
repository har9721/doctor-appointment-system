<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Models\AlcoholStatus;
use App\Models\city;
use App\Models\MstGender;
use App\Models\SmokingStatus;
use App\Models\state;
use App\Models\User;
use App\Traits\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use Home;

    public function __construct()
    {
        $this->middleware('auth',['except' => ['getCity','getState','getGender','getSmokingStatus','getAlcoholStatus']]);
    }

    public function index()
    {
        return view('home');
    }

    public function getCity(Request $request)
    {
        $stateID = $request->state_Id;

        return city::when($stateID != null, function($query) use($stateID){
            $query->where('state_id',$stateID);
        })->orderBy('name','asc')->get(['id','name']);   
    }

    public function getState()
    {
        return state::orderBy('name','asc')->get(['id','name']);   
    }

    public function getGender()
    {
        return MstGender::where('isActive',1)->get(['id','gender']);
    }

    public function getSmokingStatus()
    {
        return SmokingStatus::where('isActive',1)->get(['id','statusName']);    
    }

    public function getAlcoholStatus()
    {
        return AlcoholStatus::where('isActive',1)->get(['id','statusName']);    
    }

    public function profileView(User $user)
    {
        $result = $user->load(['city']);

        return view('admin.profile',compact('user'));    
    }

    public function updateUserDetails(UserFormRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_ID'] = Auth::user()->id;

            $updateUserInfo = User::updateUserInfo($data);

            if($updateUserInfo != '')
            {
                $response['status'] = 'success';
                $response['message'] = 'Profile details is updated successfully.';
            }else
            {
                $response['status'] = 'error';
                $response['message'] = 'Profile details is not updated successfully.';
            }

            echo json_encode($response);

        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function getAreaChartData()
    {
        return $this->getChartData();
    }

    public function getPieChartData()
    {
        return $this->fetchPieChartData();    
    }
}
