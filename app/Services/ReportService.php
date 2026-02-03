<?php

namespace App\Services;

use App\Models\Appointments;
use App\Models\Doctor;
use App\Models\Patients;
use App\Models\PaymentDetails;
use Faker\Provider\ar_EG\Payment;
use Yajra\DataTables\Facades\DataTables;

class ReportService
{
    public $appointment;
    public $doctor;
    public $patients;

    public function __construct(
        Appointments $appointments,
        Doctor $doctor,
        Patients $patients
    )
    {
        $this->appointment = $appointments;
        $this->doctor = $doctor;
        $this->patients = $patients;
    }

    public function getTrendsReport($request)
    {
        $dates = $this->getDates($request);

        $getAppointmentData = $this->appointment->tredsReportData($dates);

        return ($request->quater == "all") ?  $this->fetchFullYearData($getAppointmentData) : $getAppointmentData;
    }

    public function getDates($request)
    {
        $quater = $request->quater;
        $year = $request->year;

        switch ($quater) {
            case 'q1':
                $startDate = "$year-01-01";
                $endDate = "$year-03-31";
                break;

            case 'q2':
                $startDate = "$year-04-01";
                $endDate = "$year-06-30";
                break;

            case 'h1':
                $startDate = "$year-01-01";
                $endDate = "$year-06-30";
                break;

            case 'h2':
                $startDate = "$year-07-01";
                $endDate = "$year-12-31";
                break;

            case 'all':
                $startDate = "$year-01-01";
                $endDate = "$year-12-31";
                break;

            default:
                $startDate = "";
                $endDate = "";
                break;
        }

        return [
            'start' => $startDate,
            'end' => $endDate
        ];
    }

    public function fetchFullYearData($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->rawColumns([])
            ->make(true);
    } 

    public function getTimeSlotPreference()
    {
        $data = $this->appointment->timePreferenceData();

        return DataTables::of($data)
            ->addIndexColumn()
            // ->editColumn('timeCount', function($row){
            //     return '<a href="'.route('appointments.reports.viewReportInDetails', [
            //         'id' => $row['doctor_ID'],
            //         'status' => 'completed',
            //         'reportKey' => 'doctor'
            //     ]).'" title="View In Details" >'.$row['timeCount'].'</a>';
            // })
            ->rawColumns(['timeCount'])
            ->make(true);
    }

    public function getDoctorPerformanceReport($request)
    {
        $startDate = date('Y-m-d', strtotime($request->start_date));
        $toDate = date('Y-m-d', strtotime($request->end_date));

        $doctorPerformanceReport = $this->doctor->getDoctorPerformance($request);

        return DataTables::of($doctorPerformanceReport)
            ->addIndexColumn()
            ->editColumn('doctor_full_name', function($row){
                return "Dr." . $row->user->full_name ?? "N/A";
            })
            ->editColumn('sum_amount', function($row){
                return ($row->sum_amount == 0) ? 0.00 : '₹ '. number_format($row->sum_amount, 2);
            })
            ->editColumn('pending_count', function($row) use($startDate, $toDate){
                return ($row['pending_count'] == 0) ? 0 : '<a href="'.route('appointments.reports.viewReportInDetails', [
                    'id' => $row['id'],
                    'status' => 'pending',
                    'reportKey' => 'doctor',
                    'start' => $startDate,
                    'end' => $toDate
                ]).'" title="View In Details" >'.$row['pending_count'].'</a>';
            })
            ->editColumn('completed_count', function($row) use($startDate, $toDate){
                return ($row['completed_count'] == 0) ? 0 : '<a href="'.route('appointments.reports.viewReportInDetails', [
                    'id' => $row['id'],
                    'status' => 'completed',
                    'reportKey' => 'doctor',
                    'start' => $startDate,
                    'end' => $toDate
                ]).'" title="View In Details" >'.$row['completed_count'].'</a>';
            })
            ->editColumn('cancelled_count', function($row) use($startDate, $toDate){
                return ($row['cancelled_count'] == 0) ? 0 : '<a href="'.route('appointments.reports.viewReportInDetails', [
                    'id' => $row['id'],
                    'status' => 'cancelled',
                    'reportKey' => 'doctor',
                    'start' => $startDate,
                    'end' => $toDate
                ]).'" title="View In Details" >'.$row['cancelled_count'].'</a>';
            })
            ->rawColumns(['completed_count','cancelled_count', 'pending_count', 'sum_amount'])
            ->make(true);
    }

    public function getReportDetails($id, $status, $reportKey, $start = null, $end = null)
    {
        $data   =   $this->appointment->getAppointmentDetails($id, $status, $start, $end);

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('patients_full_name', function($row){
                return $row->patients->user->full_name ?? "N/A";
            })
            ->editColumn('appointmentDate', function($row){
                return date('d-m-Y', strtotime($row->appointmentDate));
            })
            ->editColumn('appointmentTime', function($row){
                return $row->doctorTimeSlot->time ?? "N/A";
            })
            ->rawColumns(['patients_full_name','appointmentDate'])
            ->make(true);
    }

    public function getPatientstHistory($data)
    {
        $patientsHistory = $this->appointment->fetchPatientsHistory($data);

        return DataTables::of($patientsHistory)
            ->addIndexColumn()
            ->editColumn('appointmentDate', function($row){
                return date('d-m-Y', strtotime($row['appointmentDate'])). ' ' . $row->doctorTimeSlot->time ?? 'N/A';
            })
            // ->editColumn('appointmentTime', function($row){
            //     return $row->doctorTimeSlot->time ?? 'N/A';
            // })
            ->editColumn('patients_full_name', function($row){
                return (auth()->user()->role->roleName == 'Patients') ? $row->prescriptions->doctor->user->doctor_name : $row->patients->user->patients_name;
            })
            ->editColumn('status', function($row){
                if($row['status'] === 'pending')
                {
                    $color = '<label class="badge bg-warning text-white">'.ucfirst($row['status']).'</label>';
                }else if($row['status'] === 'completed' || $row['status'] === 'confirmed')
                {
                    $color = '<label class="badge bg-success text-white">'.ucfirst($row['status']).'</label>';
                }else if($row['status'] === 'cancelled')
                {
                    $color = '<label class="badge bg-danger text-white">'.ucfirst($row['status']).'</label>';
                }else
                {
                    $color = '<label class="badge .bg-secondary text-white">'.ucfirst($row['status']).'</label>';
                }

                return $color;
            })
            ->editColumn('prescriptions', function($row){
                $viewPrescription = !empty($row->prescriptions) ? '<button name="View Prescriptions" class="mr-2 btn btn-sm btn-info border text-white prescription_summary"  data-toggle="tooltip" data-prescriptions_id = "'.$row['prescriptions']['id'].'" data-placement="bottom" title="View Prescriptions"  data-bs-toggle="modal" data-bs-target="#paymentSummaryModal">
                    <i class="fas fa-receipt"></i> 
                </button>' : '';
                
                $downloadPrescription = !empty($row->prescriptions) ? '<a href="'. route("appointments.prescription-download",['id' => $row['prescriptions']['id']]) .'">
                <button name="prescriptions" class="mr-2 btn btn-sm btn-dark border text-white download_prescriptions"  data-toggle="tooltip" data-prescriptions_id = "'.$row['prescriptions_ID'].'" data-placement="bottom" title="Download Prescriptions Summary"  data-bs-toggle="modal">
                    <i class="fas fa-download"></i> 
                </button></a>' : "";
                
                return $viewPrescription.$downloadPrescription;
            })
            ->editColumn('appointmentNo', function($row){
                return $row['appointment_no'] ?? '';
            })
            // ->editColumn('diagnosis', function($row){
            //     return '';
            // })
            ->editColumn('reason', function($row){
                return $row['reason'] ?? '' ;
            })
            ->editColumn('payment', function($row){
                $viewPaymentSummay = ($row['payment_status'] == 'completed') ? '<button name="Pay" class="mr-2 btn btn-sm btn-info border text-white payment_summary"  data-toggle="tooltip" data-id = "'.$row['id'].'" data-amount = "'. $row['amount'] .'" data-placement="bottom" title="View Payment Summary"  data-bs-toggle="modal" data-bs-target="#paymentSummaryModal">
                    <i class="fas fa-file-invoice-dollar"></i> 
                </button>' : '' ;

                $downloadInvoice = (
                    ($row['payment_status'] == 'completed') && (!empty($row->paymentDetails->res_payment_id))) ? 
                '<a href="'. route("payments.download-invoice",['link' => $row->paymentDetails->res_payment_id]) .'">
                <button name="invoice" class="mr-2 btn btn-sm btn-dark border text-white download_invoice"  data-toggle="tooltip" data-placement="bottom" title="Download Payment Summary"  data-bs-toggle="modal">
                    <i class="fas fa-download"></i> 
                </button></a>' : '' ;

                return $viewPaymentSummay.$downloadInvoice;
            })
            ->rawColumns(['appointmentDate', 'patients_full_name','prescriptions', 'status', 'payment'])
            ->make(true);
    }

    public function getRevenueDetails($request)
    {
        $startDate = date('Y-m-d', strtotime($request['from_date']));
        $endDate = date('Y-m-d', strtotime($request['to_date']));

        $revenueDetails = PaymentDetails::getRevenueDetails($startDate, $endDate);

        return $revenueDetails;
    }
}
?>