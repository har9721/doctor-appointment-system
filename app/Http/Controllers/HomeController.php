<?php

namespace App\Http\Controllers;

use App\Models\AlcoholStatus;
use App\Models\city;
use App\Models\MstGender;
use App\Models\SmokingStatus;
use App\Models\state;
use Illuminate\Http\Request;

class HomeController extends Controller
{
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

        return city::where('state_ID',$stateID)->orderBy('id','desc')->get(['id','name']);   
    }

    public function getState()
    {
        return state::get(['id','name']);   
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
}
