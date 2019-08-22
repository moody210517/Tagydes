@extends('layouts.app')

@section('page-title', trans('app.my_profile'))
@section('page-heading', trans('app.my_profile'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        @lang('app.my_profile')
    </li>

    <li class="breadcrumb-item active">
        @lang('app.two_factor_phone_verification')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="row">
    <div class="col-md-6 m-auto">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title card-title-bold">
                    @lang('app.phone_verification')
                </h5>

                <p>@lang('app.we_have_sent_you_a_verification_token')</p>

                {!! Form::open(['route' => "two-factor.verify", 'id' => 'two-factor-form']) !!}
                    @if ($user)
                        <input type="hidden" name="user" value="{{ $user }}">
                    @endif
                    <div class="form-group mt-4">
                        <input type="text"
                               class="form-control"
                               id="token"
                               name="token"
                               placeholder="@lang('app.token')">
                    </div>
                    <div class="mt-3">
                        <button type="submit"
                                class="btn btn-primary"
                                data-toggle="loader"
                                data-loading-text="@lang('app.verifying')">
                            @lang('app.verify')
                        </button>
                        <a href="javascript:;"
                           class="btn d-none"
                           id="resend-token"
                           data-loading-text="@lang('app.sending')">
                            @lang('app.resend_token')
                        </a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
    <script>
        var user = {{ isset($user) ? $user : 'null' }};
    </script>
    {!! HTML::script('assets/js/as/btn.js') !!}
    {!! HTML::script('assets/js/as/two-factor.js') !!}
@stop