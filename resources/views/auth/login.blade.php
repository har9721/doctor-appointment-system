<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Doctor Appointment System</title>
        <link rel="shortcut icon" href="{{URL::to('assets/images/doctor.png')}}" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{URL::to('css/bootstrap.min.css')}}" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="box">
            <h2>Login</h2>
            @if (Session::has('message'))
                <div class="alert alert-block alert-success" id="pwd_msg">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ Session::get('message') }}
                </div>
            @endif

            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block" id="error-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            <form id="login-form" method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="inputBox">
                    <input type="email" name="email" value="" placeholder="Username" required>
                    @error('email')
                        <span style="color: white">{{ $message }}</span>
                    @enderror
                </div>
                <div class="inputBox">
                    <input type="password" name="password" required value=""
                    placeholder="Password" required>
                    <span style="color: white">{{ $errors->first('password')}}</span>
                </div>
                <div class="submit mb-2">
                    <input type="submit" class="center" name="sign-in" value="Sign In">  
                </div>
                <a class="submit" href="{{ route('register') }}" style="color: white">Sign Up</a>
                <a class="submit" href="{{ route('forgot-password') }}" style="color: white">Forgot Password</a>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $('.close').click(function (e) {    
                $('#error-block').hide();
                $('#pwd_msg').hide();
            });
        </script>
    </body>
</html>
