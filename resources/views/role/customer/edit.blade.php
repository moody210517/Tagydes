@extends('layouts.app')

@section('page-title', trans('app.edit_customer'))
@section('page-heading', trans('app.edit_customer'))

@section('breadcrumbs')

<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.list') }}">@lang('app.customers')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.show', $customer->id) }}">{{ $customer->company_name }}</a></li>
    <li class="breadcrumb-item active">{{ trans('app.edit_customer') }}</li>
</ol>

<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">@lang('app.edit_customer')</h1>

@stop


@section('content')

@include('partials.messages')

<div class="panel panel-inverse">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            
            <!-- <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a> -->
            </div>
            <h4 class="panel-title">
                @lang('app.list')
            </h4>
        </div>
        <!-- end panel-heading -->
        <div class="panel-body">
            {!! Form::open(['route' => ['customer.update.details', $customer->id], 'method' => 'PUT', 'id' => 'details-form', 'data-parsley-validate' => 'true']) !!}
            @include('customer.partials.details', ['profile' => false])
            {!! Form::close() !!}
        </div>
    </div>

    @stop

    @section('scripts')
    {!! HTML::script('assets/js/as/btn.js') !!}
    {!! HTML::script('assets/js/as/profile.js') !!}
    {!! JsValidator::formRequest('Tagydes\Http\Requests\Customer\UpdateDetailsRequest', '#details-form') !!}

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/plugins/parsley/dist/parsley.js"></script>
    <script src="/assets/plugins/highlight/highlight.common.js"></script>
    <script src="/assets/js/demo/render.highlight.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <script>
        $(document).ready(function() {
            Highlight.init();
        });
    </script>
    @stop