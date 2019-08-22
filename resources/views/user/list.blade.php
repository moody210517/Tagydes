@extends('layouts.app')

@section('page-title', trans('app.users'))
@section('page-heading', trans('app.users'))

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

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Input::get('search') }}"
                               placeholder="@lang('app.search_for_users')">

                            <span class="input-group-append">
                                @if (Input::has('search') && Input::get('search') != '')
                                    <a href="{{ route('user.list') }}"
                                           class="btn btn-light d-flex align-items-center text-muted"
                                           role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>

                <div class="col-md-2 mt-2 mt-md-0">
                    {!! Form::select('status', $statuses, Input::get('status'), ['id' => 'status', 'class' => 'form-control input-solid']) !!}
                </div>

                <div class="col-md-6">
                    <a href="{{ route('user.create') }}" class="btn btn-primary btn-rounded float-right">
                        <i class="fas fa-plus mr-2"></i>
                        @lang('app.add_user')
                    </a>
                </div>
            </div>
        </form>

        <!-- begin row -->
			<div class="row">
				<!-- begin col-6 -->
				<div class="col-xl-12">
					<!-- begin nav-tabs -->
					<ul class="nav nav-tabs">

                        @if (count($wholesales))
                        <li class="nav-item">
							<a href="#default-tab-1" data-toggle="tab" class="nav-link {{ $tab==1? 'active':''}}">
								<span class="d-sm-none">Tab 1</span>
								<span class="d-sm-block d-none">Wholesale Users</span>
							</a>
						</li>
                        @endif

						@if (count($resellers))
                        <li class="nav-item">
							<a href="#default-tab-2" data-toggle="tab" class="nav-link {{ $tab==2? 'active':''}}">
								<span class="d-sm-none">Tab 2</span>
								<span class="d-sm-block d-none">Resellers Users</span>
							</a>
						</li>
                        @endif

                        @if (count($customers))
                        <li class="nav-item">
							<a href="#default-tab-3" data-toggle="tab" class="nav-link {{ $tab==3? 'active':''}}">
								<span class="d-sm-none">Tab 3</span>
								<span class="d-sm-block d-none">Customer Users</span>
							</a>
						</li>
                        @endif

					</ul>
					<!-- end nav-tabs -->
					<!-- begin tab-content -->
					<div class="tab-content">
						<!-- begin tab-pane -->
						<div class="tab-pane fade {{ $tab==1? 'active show':''}} " id="default-tab-1">
							<h3 class="m-t-10"><i class="fa fa-cog"></i> Manage Providers Users</h3>
							<div class="table-responsive" id="users-table-wrapper">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="min-width-80">@lang('app.username')</th>
                                        <th class="min-width-150">@lang('app.full_name')</th>
                                        <th class="min-width-100">@lang('app.email')</th>                                        
                                        <th class="min-width-80">@lang('app.registration_date')</th>
                                        <th class="min-width-80">@lang('app.status')</th>
                                        <th class="text-center min-width-150">@lang('app.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($wholesales))
                                            @foreach ($wholesales as $user)                                                
                                                @include('user.partials.row', ['tab'=>1])                                                
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            
                            {!! $wholesales->render(null, ['tab'=>'1'])  !!}

							{{-- <p class="text-right m-b-0">
								<a href="javascript:;" class="btn btn-white m-r-5">Default</a>
								<a href="javascript:;" class="btn btn-primary">Primary</a>
							</p> --}}
						</div>
						<!-- end tab-pane -->


						<!-- begin tab-pane -->
						<div class="tab-pane fade {{ $tab==2? 'active show':''}}" id="default-tab-2">
                                <h3 class="m-t-10"><i class="fa fa-cog"></i> Manage Resellers Users</h3>
                            <div class="table-responsive" id="users-table-wrapper">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="min-width-80">@lang('app.username')</th>
                                        <th class="min-width-150">@lang('app.full_name')</th>
                                        <th class="min-width-100">@lang('app.email')</th>
                                        <th class="min-width-100">Belong to</th>
                                        <th class="min-width-80">@lang('app.registration_date')</th>
                                        <th class="min-width-80">@lang('app.status')</th>
                                        <th class="text-center min-width-150">@lang('app.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($resellers))
                                            @foreach ($resellers as $user)                                                
                                                @include('user.partials.row', ['tab'=>2])                                                
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {!! $resellers->render(null, ['tab'=>'2']) !!}
						</div>
                        
                        

                        <!-- end tab-pane -->
                        <div class="tab-pane fade {{ $tab==3? 'active show':''}}" id="default-tab-3">
                                <h3 class="m-t-10"><i class="fa fa-cog"></i> Manage Customer Users</h3>
                            <div class="table-responsive" id="users-table-wrapper">
                                <table class="table table-borderless table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="min-width-80">@lang('app.username')</th>
                                        <th class="min-width-150">@lang('app.full_name')</th>
                                        <th class="min-width-100">@lang('app.email')</th>
                                        <th class="min-width-100">Belong to</th>
                                        <th class="min-width-80">@lang('app.registration_date')</th>
                                        <th class="min-width-80">@lang('app.status')</th>
                                        <th class="text-center min-width-150">@lang('app.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($customers))
                                            @foreach ($customers as $user)                                                
                                                @include('user.partials.row', ['tab'=>3])                                                
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {!! $customers->render(null, ['tab'=>'3']) !!}
						</div>
						<!-- end tab-pane -->


    </div>
</div>



@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#users-form").submit();
        });
        
    </script>
    <!-- <script>
        $(".impersonate-user-link").click(e => {
            e.preventDefault();
            $.get("{{route('impersonate.leave')}}")
                .done(data => {
                    location.href = "{{route('impersonate', 'user->id')}}";
                })
                .fail(err => {});
        });
    </script> -->
@stop
