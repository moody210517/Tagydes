@extends('layouts.app')

@section('page-title', trans('app.my_profile'))
@section('page-heading', trans('app.my_profile'))

@section('breadcrumbs')

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">@lang('app.users')</li>
</ol>

<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">@lang('app.users')</h1>

@stop

@section('content')

@include('partials.messages')

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active"
                           id="details-tab"
                           data-toggle="tab"
                           href="#details"
                           role="tab"
                           aria-controls="home"
                           aria-selected="true">
                            @lang('app.user_details')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           id="authentication-tab"
                           data-toggle="tab"
                           href="#login-details"
                           role="tab"
                           aria-controls="home"
                           aria-selected="true">
                            @lang('app.login_details')
                        </a>
                    </li>
                    @if (settings('2fa.enabled'))
                        <li class="nav-item">
                            <a class="nav-link"
                               id="authentication-tab"
                               data-toggle="tab"
                               href="#2fa"
                               role="tab"
                               aria-controls="home"
                               aria-selected="true">
                                @lang('app.two_factor_authentication')
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content mt-4" id="nav-tabContent">
                    <div class="tab-pane fade show active px-2" id="details" role="tabpanel" aria-labelledby="nav-home-tab">
                        {!! Form::open(['route' => 'profile.update.details', 'method' => 'PUT', 'id' => 'details-form']) !!}
                            @include('user.partials.details', ['profile' => true])
                        {!! Form::close() !!}
                    </div>

                    <div class="tab-pane fade px-2" id="login-details" role="tabpanel" aria-labelledby="nav-profile-tab">
                        {!! Form::open(['route' => 'profile.update.login-details', 'method' => 'PUT', 'id' => 'login-details-form']) !!}
                            @include('user.partials.auth')
                        {!! Form::close() !!}
                    </div>

                    @if (settings('2fa.enabled'))
                        <div class="tab-pane fade px-2" id="2fa" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <?php $route = Authy::isEnabled($user) ? 'disable' : 'enable'; ?>

                            {!! Form::open(['route' => "two-factor.{$route}", 'id' => 'two-factor-form']) !!}
                                @include('user.partials.two-factor')
                            {!! Form::close() !!}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'profile.update.avatar', 'files' => true, 'id' => 'avatar-form']) !!}
                    @include('user.partials.avatar', ['updateUrl' => route('profile.update.avatar-external')])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
    {!! HTML::script('assets/js/as/btn.js') !!}
    {!! HTML::script('assets/js/as/profile.js') !!}
    {!! JsValidator::formRequest('Tagydes\Http\Requests\User\UpdateDetailsRequest', '#details-form') !!}
    {!! JsValidator::formRequest('Tagydes\Http\Requests\User\UpdateProfileLoginDetailsRequest', '#login-details-form') !!}

    @if (settings('2fa.enabled'))
        {!! JsValidator::formRequest('Tagydes\Http\Requests\TwoFactor\EnableTwoFactorRequest', '#two-factor-form') !!}
    @endif
@stop