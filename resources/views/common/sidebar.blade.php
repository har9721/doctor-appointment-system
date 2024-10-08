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
            <a class="nav-link collapsed" href="{{ route('admin.doctor') }}" active>
                <i class="fas fa-user-md"></i>
                <span>Doctors</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.specialty') }}">
                <i class="fas fa-notes-medical"></i>
                <span>Doctor Speciality</span>
            </a>
        </li>
    
    @elseif(auth()->user()->role_ID == 2)

        <li class="nav-item">
            <a class="nav-link" href="{{ route('doctor.time-slot') }}">
                <i class="fas fa-calendar-check"></i>
                <span>Manage Availability</span>
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
            <a class="nav-link collapsed" href="{{ route('patients.appointment-booking') }}" active>
                <i class="fas fa-calendar"></i>
                <span>My Appointments</span>
            </a>
        </li>

    @endif
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->