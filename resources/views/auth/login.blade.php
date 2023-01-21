@extends('layouts.app')

@section('content')
    <div class="login-container sign-in-mode">
        <div class="forms-container">
            <div class="signin-signup">

                <form method="POST" action="{{ route('login') }}" class="sign-in-form">
                    @csrf
                    <h2 class="title">Sign in</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input id="email" name="email" type="email" placeholder="{{ __('E-Mail Address') }}"
                            class="@error('email') is-invalid @enderror" autofocus />
                    </div>
                    @error('email')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input id="password" name="password" type="password" placeholder="{{ __('Password') }}"
                            class="@error('password') is-invalid @enderror" name="password" />
                    </div>
                    @error('password')
                        <span class="error invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="remember">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">{{ __('Remember Me') }}</label>
                        </div>
                    </div>
                    <div class="submit-field">
                        {!! RecaptchaV3::field('login') !!}
                        <input type="submit" value="{{ __('Login') }}" class="btn solid" />
                    </div>
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h1 style="font-size:50px;">Book Store</h1>
                    @if (Route::has('register'))
                        {{-- <a class="btn transparent" href="{{ route('register') }}">{{ __('Sign up') }}</a> --}}
                    @endif
                </div>
                <img src="{{ asset('images/log.png') }}" class="image" alt="" />
            </div>
            {{-- <div class="panel right-panel">
                <div class="content">
                    <h3>One of us ?</h3>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum
                        laboriosam ad deleniti.
                    </p>
                    <button class="btn transparent" id="sign-in-btn">
                        Sign in
                    </button>
                </div>
                <img src="{{ asset('images/register.svg') }}" class="image" alt="" />
            </div> --}}
        </div>
    </div>
@endsection
{!! RecaptchaV3::initJs() !!}
