@extends('layouts.auth')

@section('page-title', trans('app.login'))

@section('content')

<form role="form" action="<?= url('login') ?>" method="POST" id="login-form" autocomplete="off" class="margin-bottom-0">
    <input type="hidden" value="<?= csrf_token() ?>" name="_token">
    @if (Input::has('to'))
    <input type="hidden" value="{{ Input::get('to') }}" name="to">
    @endif
    <div class="form-group m-b-15">
        <input type="text" class="form-control form-control-lg" placeholder="Email Address" name="username" id="username" required />
    </div>
    <div class="form-group m-b-15">
        <input type="password" class="form-control form-control-lg" placeholder="Password" name="password" id="password" required />
    </div>
    <div class="checkbox checkbox-css m-b-30">
        <input type="checkbox" id="remember_me_checkbox" value="" />
        <label for="remember_me_checkbox">
            Remember Me
        </label>
    </div>
    <div class="login-buttons">
        <button type="submit" class="btn btn-primary btn-block btn-lg">Sign me in</button>
    </div>
    <div class="m-t-20 m-b-40 p-b-40">
        Not a member yet? Click <a href="register_v3.html">here</a> to register.
    </div>
    <hr />
    <p class="text-center">
        &copy; Tagydes All Right Reserved {{ now()->year }}
    </p>
</form>

@if (settings('forgot_password'))
<div class="text-center text-muted">
    <a href="<?= url('password/remind') ?>" class="forgot">@lang('app.i_forgot_my_password')</a>
</div>
<br/>
@endif
<div class="text-center text-muted">
    @if (settings('reg_enabled'))
    @lang('app.dont_have_an_account') <a class="font-weight-bold" href="<?= url("register") ?>">Sign Up</a>
    @endif
</div>
@stop

@section('scripts')
{!! HTML::script('assets/js/as/login.js') !!}
{!! JsValidator::formRequest('Tagydes\Http\Requests\Auth\LoginRequest', '#login-form') !!}
@stop