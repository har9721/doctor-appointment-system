<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3"> @if (Auth::user() != '')
            {{ Auth::user()->name }}          
            @endif
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @if(auth()->user()->role_ID == 1)
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.patients') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Patients</span>
            </a>
        </li>

        <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.specialty') }}">
                <i class="fas fa-notes-medical"></i>
                <span>Doctor Speciality</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.doctor') }}" active>
                <i class="fas fa-user-md"></i>
                <span>Doctors</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('appointments.my-appointments') }}" active>
                <i class="fas fa-calendar"></i>
                <span>Appointments</span>
            </a>
        </li>
    
    @elseif(auth()->user()->role_ID == 2)

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.patients') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Patients</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('doctor.time-slot') }}">
                <i class="fas fa-calendar-check"></i>
                <span>Manage Availability</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('appointments.my-appointments') }}" active>
                <i class="fas fa-calendar"></i>
                <span>Appointments</span>
            </a>
        </li>
    
    @else

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('patients.appointment-booking') }}" active>
                <i class="fas fa-calendar"></i>
                <span>Appointment Booking</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('appointments.my-appointments') }}" active>
                <i class="fas fa-calendar"></i>
                <span>My Appointments</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('appointments.completed-list') }}" active>
                <i class="fas fa-check-circle"></i>
                <span>Completed Appointments</span>
            </a>
        </li>

    @endif

    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('appointments-history',['role' => strtolower(Auth::user()->role->roleName)]) }}" active>
            <i class="fas fa-calendar"></i>
            <span>Appointments History</span>
        </a>
    </li> -->

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-file"></i>
            <span>Reports</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- common reports -->
                <a class="collapse-item" href="{{ route('appointments-history',['role' => strtolower(Auth::user()->role->roleName)]) }}">
                    Appointments History
                </a>

                <a class="collapse-item" href="{{ route('patients.reports.view-history') }}">
                    Patient Visit History
                </a> 

                @if(auth()->user()->role->roleName == 'Admin' || auth()->user()->role->roleName == 'Doctor')
                    <a class="collapse-item" href="{{ route('appointments.reports.trends') }}">
                        Appointment Trend
                    </a>

                    <a class="collapse-item" href="{{ route('appointments.reports.time-preference') }}">
                        Booking Time Preferences
                    </a>

                    <a class="collapse-item" href="{{ route('appointments.reports.doctorPerformanceReport') }}">
                        Doctor Performance
                    </a>
                @endif

                @if(auth()->user()->role->roleName == 'Admin')
                    <a class="collapse-item" href="{{ route('admin.get-revenue-report') }}">
                        Monthly Revenue Reports
                    </a>
                @endif
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->