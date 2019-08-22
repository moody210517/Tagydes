@extends('layouts.app')

@section('page-title', trans('app.customers'))
@section('page-heading', trans('app.customers'))

@section('breadcrumbs')
@if(!$customer)
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">@lang('app.customers')</li>
</ol>

<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">@lang('app.customer_table')</h1>
@else
<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.list') }}">@lang('app.customers')</a></li>
    <li class="breadcrumb-item active">@lang('app.branch_office')</li>
</ol>

<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">@lang('app.branch_table')</h1>
@endif
@stop

@section('content')

@include('partials.messages')

<div class="panel panel-inverse">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
            <!-- <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a> -->
        </div>
        <h4 class="panel-title">
            @lang('app.list')
        </h4>
    </div>
    <!-- end panel-heading -->
    <div class="panel-body">

        <form action="" method="GET" id="customers-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                        class="form-control input-solid"
                        name="search"
                        value="{{ Input::get('search') }}"
                        placeholder="@lang('app.search_for_customers')">

                        <span class="input-group-append">
                            @if (Input::has('search') && Input::get('search') != '')
                            <a href="{{ route('customer.list') }}"
                            class="btn btn-light d-flex align-items-center text-muted"
                            role="button">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                        <button class="btn btn-light" type="submit" id="search-customers-btn">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                    </span>
                </div>
            </div>

            <div class="col-md-2 mt-2 mt-md-0">
                {!! Form::select('status', $statuses, Input::get('status'), ['id' => 'status', 'class' => 'form-control input-solid']) !!}
            </div>
            @if (Auth::user()->hasRole('Reseller', 'Branch Office'))
            @if(!$customer)
            <div class="col-md-6">
                <a href="{{ route('customer.create') }}" class="btn btn-white btn-rounded float-right">
                    <i class="fas fa-plus mr-2"></i>
                    @lang('app.add_customer')
                </a>
            </div>
            @endif
            @endif
        </div>
        
        @if($customer)
        <div class="row my-3 flex-md-row flex-column-reverse">
            <div class="col-md-12 mt-md-0 mt-2">
                @lang('app.showing_branch_offices_for_customer') {{ $customer->company_name }}
            </div>
        </div>
        @endif
    </form>

    <div class="table-responsive" id="customers-table-wrapper">
        
        <table id="data-table-responsive" class="table table-striped table-bordered">
            
            <thead>
                <tr>
                    <th class="min-width-80 text-nowrap">@lang('app.company_name')</th>
                    <th class="min-width-150">@lang('app.reseller')</th>
                    <!-- <th class="min-width-150">@lang('app.address')</th> -->
                    <th class="min-width-100">@lang('app.branch_office')</th>
                    <th class="min-width-80">@lang('app.country')</th>
                    <th class="min-width-80">@lang('app.status')</th>
                    <th class="text-center min-width-150">@lang('app.action')</th>
                </tr>
            </thead>

            <tbody>
                @if (count($customers))
                @foreach ($customers as $customer)
                @include('customer.partials.row')
                @endforeach
                @else
                <tr>
                    <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
</div>

{!! $customers->render(null, ['tab'=>'']) !!}

@stop

@section('scripts')
<script>
    $("#status").change(function () {
        $("#customers-form").submit();
    });
</script>
@stop
