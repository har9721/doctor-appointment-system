<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    {{-- <form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form> --}}

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline small" style="color: black;"><b>@if (Auth::user() != '') {{ ucfirst(Auth::user()->first_name). ' '. ucfirst(Auth::user()->last_name)  }} <p>({{ Auth::user()->role->roleName }})</p> @endif</b></span>
                <img class="img-profile rounded-circle" src="{{ asset('Images/avatar.png') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                @php 
                    if (Auth::user()->role_ID == 1) {
                        $id = Auth::user()->id;
                        $routeName = 'admin.profile';
                        $param = 'user';
                    } elseif(Auth::user()->role_ID == 2) {
                        $id = App\Models\Doctor::getLoginDoctorID(); 
                        $routeName = 'admin.editDoctorDetails';
                        $param = 'doctor';
                    }else{
                        $id = App\Models\Patients::getLoginPatientsId(); 
                        $routeName = 'patients.edit-patients';
                        $param = 'patients';
                    }
                @endphp

                <a class="dropdown-item" href="{{ route($routeName, [$param => $id]) }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    My Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>

</nav>
<!-- End of Topbar -->