@extends('layouts.auth')

@section('page-title', trans('app.reset_password'))

@section('content')

<div class="col-md-8 col-lg-6 col-xl-5 mx-auto my-10p">
    <div class="text-center">
        <img src="{{ url('assets/img/vanguard-logo.png') }}" alt="{{ settings('app_name') }}" height="50">
    </div>

    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title text-center mt-4 mb-2 text-uppercase">
                @lang('app.reset_your_password')
            </h5>

            @include('partials.messages')

            <form role="form" action="{{ url('password/reset') }}" method="POST" id="reset-password-form" autocomplete="off" class="p-4">
                <input type="hidden" name="token" value="{{ $token }}">
                {{ csrf_field() }}

                <p class="text-muted mb-4 text-center font-weight-light px-2">
                    @lang('app.pick_new_password_below')
                </p>

                <div class="form-group">
                    <label for="password" class="sr-only">@lang('app.your_email')</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="@lang('app.your_email')">
                </div>

                <div class="form-group">
                    <label for="password" class="sr-only">@lang('app.new_password')</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="@lang('app.new_password')">
                </div>

                <div class="form-group">
                    <label for="password" class="sr-only">@lang('app.confirm_new_password')</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="@lang('app.confirm_new_password')">
                </div>

                <div class="form-group mt-5">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-reset-password">
                        @lang('app.update_password')
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@stop

@section('scripts')
    {!! JsValidator::formRequest('Tagydes\Http\Requests\Auth\PasswordResetRequest', '#reset-password-form') !!}
@stop