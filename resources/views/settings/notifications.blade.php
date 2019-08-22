@extends('layouts.app')

@section('page-title', trans('app.notification_settings'))
@section('page-heading', trans('app.notification_settings'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">
        @lang('app.settings')
    </li>
    <li class="breadcrumb-item active">
        @lang('app.notifications')
    </li>
@stop

@section('content')

@include('partials.messages')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="panel-heading"></div>
            <div class="card-body">
                <h5 class="card-title">
                    @lang('app.email_notifications')
                </h5>

                {!! Form::open(['route' => 'settings.notifications.update', 'id' => 'notification-settings-form']) !!}

                    <div class="form-group my-4">
                        <div class="d-flex align-items-center">
                            <div class="switch">
                                <input type="hidden" value="0" name="notifications_signup_email">

                                <input type="checkbox"
                                       name="notifications_signup_email"
                                       class="switch"
                                       value="1"
                                       id="switch-signup-email"
                                       {{ settings('notifications_signup_email') ? 'checked' : '' }}>

                                <label for="switch-signup-email"></label>
                            </div>
                            <div class="ml-3 d-flex flex-column">
                                <label class="mb-0">@lang('app.sign_up_notification')</label>
                                <small class="pt-0 text-muted">
                                    @lang('app.notify_admin_when_user_signs_up')
                                </small>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        @lang('app.update_settings')
                    </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop