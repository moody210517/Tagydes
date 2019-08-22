@extends('layouts.app')

@section('page-title', trans('app.add_customer'))
@section('page-heading', trans('app.create_new_customer'))

@section('breadcrumbs')
    
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.list') }}">@lang('app.customers')</a></li>
    <li class="breadcrumb-item active">@lang('app.create')</li>
</ol>

<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">@lang('app.customer') <small>@lang('app.registration_form')</small></h1>

@stop

@section('content')

@include('partials.messages')

{!! Form::open(['route' => 'customer.store', 'files' => false, 'id' => 'customer-form']) !!}

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('app.customer_details')
                    </h5>
                    <p class="text-muted font-weight-light">
                        A general customer profile information.
                    </p>
                </div>
                <div class="col-md-9">
                    @include('customer.partials.details', ['edit' => false, 'profile' => false])
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('app.login_details')
                    </h5>
                    <p class="text-muted font-weight-light">
                        Details used for authenticating with the application.
                    </p>
                </div>
                <div class="col-md-9">
                    @include('customer.partials.auth', ['edit' => false])
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                @lang('app.create_customer')
            </button>
        </div>
    </div>
{!! Form::close() !!}

<br>
@stop

@section('scripts')
    {!! HTML::script('assets/js/as/profile.js') !!}
    {!! JsValidator::formRequest('Tagydes\Http\Requests\Customer\CreateCustomerRequest', '#customer-form') !!}
@stop