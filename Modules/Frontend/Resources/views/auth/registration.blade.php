@extends('frontend::layouts.auth_layout')

@section('content')

<div id="login" >
    <div class="vh-100" style="background-image: url('{{ asset('/dummy-images/login_banner.jpg') }}')">
        <div class="container">
            <div class="row justify-content-center align-items-center height-self-center vh-100">
                <div class="col-lg-5 col-md-8 col-11 align-self-center">
                    <div class="user-login-card card my-5">
                        <div class="text-center auth-heading">
                            <a href="{{route('home')}}">
                                <img src="{{ asset(setting('logo')) }}" class="img-fluid logo h-4 mb-4">
                            </a>
                            <h5>{{ __('frontend.sign_up_title') }}</h5>
                            <p class="font-size-14">{{ __('frontend.sign_sub_title') }}</p>
                        </div>
                        <p class="text-danger" id="error_message"></p>
                        <form  action="{{route('store-user')}}"  method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text px-0"><i class="ph ph-user"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="{{ __('frontend.user_name') }}"  >
                                @error('username')
                                    <span class=" text-danger w-100 text-sm ms-3">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text px-0"><i class="ph ph-lock-key"></i></span>
                                <input type="password" name="password" class="form-control" id="password" placeholder="{{ __('frontend.password') }}" >
                                <span class="input-group-text px-0"><i class="ph ph-eye" id="togglePassword"></i></span>
                                @error('password')
                                    <span class=" text-danger w-100 text-sm ms-3">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text px-0"><i class="ph ph-lock-key"></i></span>
                                <input type="password" name="password_confirmation" class="form-control" id="confirm_password" placeholder="{{ __('frontend.confirm_password') }}"  >
                                <span class="input-group-text px-0"><i class="ph ph-eye" id="toggleConfirmPassword"></i></span>
                                @error('confirm_password')
                                    <span class=" text-danger w-100 text-sm ms-3">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="full-button text-center">
                                <button type="submit" class="btn btn-custom-button-one w-100" data-signup-text="{{ __('frontend.sign_up') }}">
                                    {{ __('frontend.sign_up') }}
                                </button>
                                <p class="mt-2 mb-0 fw-normal"> {{ __('frontend.already_have_account') }} <a href="{{ route('login') }}" class="ms-1">{{ __('frontend.login') }}</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="{{ asset('js/auth.min.js') }}" defer></script> --}}
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('ph-eye-slash');
    });

    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirm_password = document.querySelector('#confirm_password');
    if (toggleConfirmPassword) {

    toggleConfirmPassword.addEventListener('click', function () {
        const type_confirm = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
        confirm_password.setAttribute('type', type_confirm);
        this.classList.toggle('ph-eye-slash');
    });

    }
</script>
@endsection
