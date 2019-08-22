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
                @lang('app.forgot_your_password')
            </h5>

            @include('partials.messages')

            <div class="p-4">
                <form role="form" action="<?= url('password/remind') ?>" method="POST" id="remind-password-form" autocomplete="off">
                    {{ csrf_field() }}

                    <p class="text-muted mb-4 text-center font-weight-light">
                        @lang('app.please_provide_your_email_below')
                    </p>

                    <div class="form-group password-field my-3">
                        <label for="password" class="sr-only">@lang('app.email')</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="@lang('app.your_email')">
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-reset-password">
                            @lang('app.reset_password')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@stop

@section('scripts')
    {!! JsValidator::formRequest('Tagydes\Http\Requests\Auth\PasswordRemindRequest', '#remind-password-form') !!}
@stop