<?php 

namespace App\Traits;

use App\Models\Appointments;
use Illuminate\Support\Facades\Auth;

trait Home
{
    protected $total_appointments = 0;
    protected $monthly_appointments = 0;
    protected $pending_appointments = 0;
    protected $completed_appointments = 0;
    protected $cancelled_appointments = 0;
    protected $pending_payments = 0;
    protected $completed_payments = 0;

    public function getDashboardData()
    {
        $user = Auth::user()->role ? Auth::user()->role->roleName : null;

        $appointment = new Appointments();

        switch ($user) {
            case 'Admin':
               return $this->getAdminAndPatientsDashboardData($appointment,$user);
                break;
            case 'Doctor':
                return $this->getDoctorDashboardData($appointment);
                break;
            
            case 'Patients':
                return $this->getAdminAndPatientsDashboardData($appointment,$user);
                break;

            default:
                # code...
                break;
        }
    }

    public function getAdminAndPatientsDashboardData($appointment,$user)
    {
        $this->total_appointments = $appointment->getAppointmentCount(null,null,null,$user);

        $this->monthly_appointments = $appointment->getAppointmentCount(null,date('m'),null,$user);

        $this->pending_appointments = $appointment->getAppointmentCount('pending',null,null,$user);
                                    
        $this->completed_appointments = $appointment->getAppointmentCount('completed',null,null,$user);

        $this->cancelled_appointments = $appointment->getAppointmentCount('cancelled',null,null,$user);

        $this->pending_payments = $appointment->getAppointmentCount(null,null,'pending',$user);

        $this->completed_payments = $appointment->getAppointmentCount(null,null,'completed',$user);

        return $this->getCardsConfig();
    }

    public function getCardsConfig()
    {
        return [ 
            'total_appointments' => [
                'heading' => 'Appointments (Annual)', 'color' => 'success', 'icon' => 'fas fa-calendar fa-2x text-gray-300', 'count' => $this->total_appointments, 'link' => '#'
            ],

            'monthly_appointments' => [
                'heading' => 'Appointments (Monthly)', 'color' => 'primary', 'icon' => 'fas fa-calendar fa-2x text-gray-300', 'count' => $this->monthly_appointments, 'link' => '#'
            ],

            'pending_appointments' => [
                'heading' => 'Pending Appointments', 'color' => 'warning', 'icon' => 'fas fa-calendar fa-2x text-gray-300', 'count' => $this->pending_appointments, 'link' => '#'
            ],

            'completed_appointments' => [
                'heading' => 'Completed Appointments', 'color' => 'info', 'icon' => 'fas fa-calendar fa-2x text-gray-300', 'count' => $this->completed_appointments, 'link' => '#'
            ],

            'cancelled_appointments' => [
                'heading' => 'Cancelled Appointments', 'color' => 'secondary', 'icon' => 'fas fa-calendar fa-2x text-gray-300', 'count' => $this->cancelled_appointments, 'link' => '#'
            ],

            'pending_payments' => [
                'heading' => 'Pending Payments', 'color' => 'danger', 'icon' => 'fas fa-credit-card fa-2x text-gray-300', 'count' => $this->pending_payments, 'link' => '#'
            ],

            'completed_payments' => [
                'heading' => 'Completed Payments', 'color' => 'dark', 'icon' => 'fas fa-credit-card fa-2x text-gray-300', 'count' => $this->completed_payments, 'link' => '#'
            ]
        ];    
    }

    public function getDoctorDashboardData($appointment)
    {
        $this->total_appointments = $appointment->getDoctorDashboardData();

        $this->monthly_appointments = $appointment->getDoctorDashboardData(null,date('m'));

        $this->pending_appointments = $appointment->getDoctorDashboardData('pending');
                                    
        $this->completed_appointments = $appointment->getDoctorDashboardData('completed');

        $this->cancelled_appointments = $appointment->getDoctorDashboardData('cancelled');

        $this->pending_payments = $appointment->getDoctorDashboardData(null,null,'pending');

        $this->completed_payments = $appointment->getDoctorDashboardData(null,null,'completed');

        return $this->getCardsConfig();
    }

    public function getChartData()
    {
        return  Appointments::getAppointmentsSum();
    }

    public function fetchPieChartData()
    {
        if(Auth::user()->isDoctor())
        {
            return Appointments::getPatientOverview('completed');    
        }
        else if(Auth::user()->isAdmin())
        {
            return Appointments::getPieChartData();   
        } 
    }

    public function fetchAppointments()
    {
        return [
            'upcoming_appointments' => Appointments::getUpcomingAppointments('pending'),
            'completed_appointments' => Appointments::getUpcomingAppointments('completed'),
        ];
    }
}
?>