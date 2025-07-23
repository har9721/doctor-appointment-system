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
}
