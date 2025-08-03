<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    private $reportService;

    public function __construct(ReportService $report_service)
    {
        $this->reportService = $report_service;
    }

    public function viewAppointmentsTrends()
    {
        $month = date('m');
        $endMonth = ($month < 6) ? 06 : 12;
        $quater = ($month < 6) ? 'q1' : 'q2';

        return view('reports.appointments.trends', compact(['quater']));    
    }

    public function fetchTrendsData(Request $request)
    {
        if($request->ajax())
        {
            $data = $this->reportService->getTrendsReport($request);

            return $data;
        }
    }

    public function showTimePreference()
    {
        return view('reports.appointments.timePreference');    
    }

    public function fetchTimePreference()
    {
        return $this->reportService->getTimeSlotPreference();
    }

    public function viewDoctorPerformanceReport()
    {
        return view('reports.appointments.doctorPerformance');    
    }

    public function fetchDoctorPerformance(Request $request)
    {
        return $this->reportService->getDoctorPerformanceReport($request);
    }

    public function viewReportInDetails($id, $status, $reportKey, $start = null, $end = null)
    {
        return view('reports.appointments.appointmentDetails', compact(['id','status', 'reportKey', 'start', 'end']));
    }

    public function fetchAppointmentDetails(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $reportKey = $request->reportKey;
        $start = $request->start;
        $end = $request->end;

        return $this->reportService->getReportDetails($id,  $status,  $reportKey, $start, $end);
    }

    public function viewPatientsHistory()
    {
        return view('reports.patientHistory');
    }

    public function fetchPatientHistory(Request $request)
    {
        return $this->reportService->getPatientstHistory($request->only(['id','from_date','to_date']));
    }
}
