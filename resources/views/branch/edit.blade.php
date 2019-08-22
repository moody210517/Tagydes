@extends('layouts.app')

@section('page-title', trans('app.add_customer'))
@section('page-heading', trans('app.create_new_customer'))


@section('content')

@include('partials.messages')


{!! Form::open(['route' => 'branch.updateBranch', 'files' => false ]) !!}


    <div class="card">
        <div class="card-body">

            <input type="hidden" id="branch_id" name="branch_id" value="{{$branch->id}}" />
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
                        name="title" placeholder="Branch Name" value="{{$branch->title}}" required>
                        </div>                    
                </div>
                <div class="col-md-3">                
                        <div class="form-group">
                            <label for="postal_code">Address</label>
                            <input type="text" class="form-control" id="address"
                            name="address" placeholder="Address" value="{{$branch->address}}" >
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
                     <input type="hidden" name="num" value="<?= count($users) ?>">
                         @if (count($users) > 0)
                         <?php $i=0; ?>
                         @foreach ($users as $user)             
                             <tr >                                                                                                                                                      
                                 <td class="with-checkbox">
                                     <div class="checkbox checkbox-css">
                                         <input type="checkbox"  onclick="handleClick(this);"  name="user_lists[]"  class="checkboxes"  value="{{$user->id}}" id="{{$user->id}}"  <?php echo $user->getBanch($user, $branch->id)->first()?'checked':''  ?>/>
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
                        <?php $i++; ?>
                         @endforeach
                         @else
                         <tr>
                             <td colspan="7"><em>@lang('app.no_records_found')</em></td>
                         </tr>
                         @endif
                     </tbody>
                 </table>                            
            </div>
    

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        Update Branch
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
    var bId;
    $(document).ready(function() {        
        bId = document.getElementById("branch_id").value;
    });

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
                
            } else {                
            }
        });
    });   


    

    function handleClick(cb){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });

            $.ajax({
            type: 'GET',
            data: { checked:cb.checked, user_id:cb.value, branch_id:bId},
            contentType: "application/json",
            //crossDomain : true,
            url: "{{ url('udpateUserBranch')}}",
            dataType: "json",
            success: function(result) {     
                    var res = result.results;
                    if(res == 200){
                       // alert("success");
                    }else{
                        alert("Failed");
                    }
            }

            });

    }
   
</script>
@stop
