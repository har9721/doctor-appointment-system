<?php

namespace App\Http\Controllers;

use App\Http\Requests\validatePrescriptions;
use App\Jobs\SendPrescriptionMail;
use App\Models\Prescriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrescriptionsController extends Controller
{
    public function addPrescriptions(validatePrescriptions $request)
    {
        try {
            $data = $request->validated();

            if($data['hidden_mode'] === 'edit')
            {
                $add_prescription = Prescriptions::updatePrescriptions($data);
                $message = "updated";
            }else{
                $add_prescription = Prescriptions::addPrescriptions($data);
                $message = "added";
            }

            if($add_prescription != null)
            {
                $response['status'] = "success";
                $response['message'] = "Prescription $message successfully.";

                // dispatch the job
                dispatch(new SendPrescriptionMail($add_prescription,$data['hidden_mode']));

            }else{
                $response['status'] = "error";
                $response['message'] = "Prescription not $message successfully.";
            }
        }catch (\Exception $e) {
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
    }

    public function fetchPrescriptions(Request $request)
    {
        if($request->ajax())
        {
            $prescription_details = Prescriptions::when($request->prescription_id,function($query) use($request){
                $query->where('id',$request->prescription_id);
            })
            ->where('isActive',1)
            ->get(['id','appointment_ID','doctor_ID','patient_ID','medicines','instructions']);

            return (!empty($prescription_details) && isset($prescription_details[0])) ? response()->json($prescription_details[0]) : '';
        }
    }

    public function downloadPrescriptions($prescription_id)
    {
        $filePath = "public/Prescriptions/prescrip_$prescription_id.pdf";

        if (!Storage::exists($filePath)) {
            abort(404, "Prescription not found.");
        }

        $random_str = Str::random(5);

        return Storage::download($filePath, "prescription_$random_str.pdf");
    }
}
