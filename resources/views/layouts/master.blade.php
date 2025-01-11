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
    <link href="{{ URL::to('assets/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ URL::to('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    @stack('style')
</head>
<body class="bg-gradient-primary">
    <div id="app" class="container">
        <div class="d-flex flex-column">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ URL::to('assets/js/jquery.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ URL::to('assets/js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
