<?php

namespace App\Services;

use App\Models\Appointments;
use Yajra\DataTables\Facades\DataTables;

class ReportService
{
    public $appointment;

    public function __construct(Appointments $appointments)
    {
        $this->appointment = $appointments;
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
        $year = date('Y');

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
            ->rawColumns([])
            ->make(true);
    }
}
?>