@extends('layouts.master')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if($email == "")
                    <div class="card-header"></div>
                        <div class="card-body d-flex justify-content-center">
                            <h2><strong>Thank You.</strong></h2>
                        </div>
                @else
                    <div class="card-header">
                        <h3>{{ __('Reset Password') }}</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('reset.password.post') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email }}" disabled required autocomplete="email" autofocus placeholder="Enter Email...">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="input-group col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter Password...">

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="bi bi-eye-slash mb-3" id="togglePassword"></i>
                                        </span>
                                    </div>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="input-group col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Enter Confirm Password...">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="bi bi-eye-slash mb-3" id="toggleCPassword" ></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0 mt-5">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        var togglePassword = document.querySelector('#togglePassword');
        var password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            var type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('bi-eye');
        });

        var toggleCnfPassword = document.querySelector('#toggleCPassword');
        var passwordCnf = document.querySelector('#password-confirm');

        toggleCnfPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            var type = passwordCnf.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordCnf.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('bi-eye');
        });
    });
</script>
@endsection
