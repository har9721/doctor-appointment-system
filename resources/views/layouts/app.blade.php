<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Book Appoinment</title>
    <link rel="shortcut icon" href="{{URL::to('assets/images/doctor.png')}}" />
    <!-- Custom fonts for this template-->
    <link href="{{ URL::to('assets/fontawesome-free/css/all.min.css') }}" rel="stylesheet" >
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ URL::to('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- toggle switch -->
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    @yield('bootstrap')
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('common.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                @include('common.header')
                @yield('content')
                @include('common.footer')
            </div>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('logout')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">Are you Sure to Logout?</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ URL::to('assets/js/jquery.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ URL::to('assets/js/sb-admin-2.min.js') }}"></script>
    
    <!-- toggle switch -->
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{URL::to('assets/js/sweetalert2.js')}}"></script>

    @stack('scripts')
</body>
</html>