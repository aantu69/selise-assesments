@extends('layouts.app')

@section('content')
    <div class="login-container sign-up-mode">
        <div class="forms-container">
            <div class="signin-signup">
                <form method="POST" action="{{ route('register') }}" class="sign-up-form">
                    @csrf
                    <h2 class="title">Sign up</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input id="name" name="name" type="text" placeholder="{{ __('Name') }}"
                            class="@error('name') is-invalid @enderror" value="{{ old('name') }}" required
                            autocomplete="name" autofocus />
                    </div>
                    @error('name')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input id="email" name="email" type="email" placeholder="{{ __('E-Mail Address') }}"
                            class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required
                            autocomplete="email" />
                    </div>
                    @error('email')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input id="password" name="password" type="password" placeholder="{{ __('Password') }}"
                            class="@error('password') is-invalid @enderror" value="{{ old('password') }}" required
                            autocomplete="password" />
                    </div>
                    @error('password')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="{{ __('Confirm Password') }}" value="{{ old('password_confirmation') }}" required
                            autocomplete="password_confirmation" />
                    </div>
                    <div class="remember">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                    </div>
                    @error('g-recaptcha-response')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="remember">
                        <input type="submit" value="{{ __('Register') }}" class="btn solid" />
                    </div>
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>New here ?</h3>
                    <p>
                        Already have an account?
                    </p>
                    <a class="btn transparent" href="{{ route('register') }}">{{ __('Sign up') }}</a>
                </div>
                <img src="{{ asset('images/log.png') }}" class="image" alt="" />
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>One of us ?</h3>
                    <p>
                        if you already have an account, please login.
                    </p>
                    <a class="btn transparent" href="{{ route('login') }}">{{ __('Sign in') }}</a>
                </div>
                <img src="{{ asset('images/register.svg') }}" class="image" alt="" />
            </div>
        </div>
    </div>
@endsection
