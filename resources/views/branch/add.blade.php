@extends('layouts.app')

@section('page-title', trans('app.add_customer'))
@section('page-heading', trans('app.create_new_customer'))
@section('content')
@include('partials.messages')

{!! Form::open(['route' => 'branch.store', 'files' => false, 'id' => 'customer-form']) !!}
    
    <div class="card">
        <div class="card-body">
               <div class="row">
                    <div class="col-md-3">
                        <h5 class="card-title">
                            Details
                        </h5>
                        <p class="text-muted font-weight-light">
                            Branch Office Name.
                        </p>
                    </div>
                    <div class="col-md-3">                
                        <div class="form-group">
                            <label for="branch_name">Branch Name</label>
                            <input type="text" class="form-control" id="title"
                            name="title" placeholder="Branch Name" value="" required>
                            </div>                    
                    </div>
                    <div class="col-md-3">                
                            <div class="form-group">
                                <label for="postal_code">Address</label>
                                <input type="text" class="form-control" id="address"
                                name="address" placeholder="Address" value="" >
                            </div>                    
                    </div>        
                </div>



                <div class="row">
                 
                    
                            <table id="data-table-responsive" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="with-checkbox">                                        
                                            <div class="checkbox checkbox-css">
                                                <input type="checkbox"  class="group-checkable"  value="" id="table_checkbox_1" />
                                                <label for="table_checkbox_1">&nbsp;</label>
                                            </div>
                                        </th>                                
                                        <th>Username</th>
                                        <th>Email Address</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($users) > 0)
                                    @foreach ($users as $user)                    
                                        <tr >                                                                                                                                                      
                                            <td class="with-checkbox">
                                                <div class="checkbox checkbox-css">
                                                    <input type="checkbox"  name="user_lists[]"  class="checkboxes"  value="{{$user->id}}" id="{{$user->id}}" />
                                                    <label for="{{$user->id}}">&nbsp;</label>
                                                </div>
                                            </td>                                    
                                            <td class="align-middle"> 
                                            {{$user->username}}
                                            </td>
                                            <td class="align-middle"> 
                                            {{ $user->email}}
                                            </td>                                                                    
                                        </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        
                  
                   

                </div>


                <div class="pb-2 border-bottom-light">
                    <div class="col-md-12 mb-3 pb-2">
                        <button type="submit" class="btn btn-primary btn-rounded float-right">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('app.create_branch')
                        </button>
                    </div>
                </div>
                
                        
        </div>

    </div>

    
    
{!! Form::close() !!}

<br>
@stop

@section('scripts')

<script type="text/javascript">   

    $(document).on('click', '.group-checkable', function () {        
        var checked = $(this).is(":checked");
        var value = $(this).val();
        $checkBoxs = $(this).closest("table").find("tbody .checkboxes");        
        $checkBoxs.each( function () {
            $( this ).prop( "checked", checked);
        });        
        // only enable button when choose a row
        //document.getElementById("deleteUser").disabled = true;
        $checkBoxs.each( function () {
            var checked = $(this).is(":checked");
            var value = $(this).val();
            if (checked) {                
                //document.getElementById("deleteUser").disabled = false;
            } else {                
            }
        });

    });   

</script>
@stop