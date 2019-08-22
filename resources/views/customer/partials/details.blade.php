    <div class="row">
        <div class="col-md-4">

            <div class="form-group">
                <label for="status">@lang('app.status')</label>
                @if ($edit || $create)
                {!! Form::select('status', $statuses, $edit ? $customer->status : '',
                ['class' => 'form-control', 'id' => 'status', $profile ? 'disabled' : '']) !!}
                @else
                <input type="text" disabled="disabled" id="statuses" value="{{$customer->status}}" class="form-control" />
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="nif">@lang('app.nif')</label>
                <input type="text" class="form-control {{ $errors->has('nif') ? 'parsley-error' : '' }}" id="nif"
                name="nif" placeholder="@lang('app.nif')" value="{{ $edit ? $customer->nif : old('nif') }}" data-parsley-required="true" minlength="5" >

            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="postal_code">@lang('app.postal_code')</label>
                <input type="text" class="form-control" id="postal_code"
                name="postal_code" placeholder="@lang('app.postal_code')" value="{{ $edit ? $customer->postal_code : $view ? $customer->postal_code : old('postal_code') }}" {{ $view ? 'disabled' : ''}}>
            </div>
        </div>

        
    </div>

    <div class="row">
        <div class="col-md-6">

            <div class="form-group">
                <label for="company_name">@lang('app.company_name')</label>
                <input type="text" class="form-control" id="company_name"
                name="company_name" required placeholder="@lang('app.company_name')" value="{{  $edit ? $customer->company_name : old('company_name') }}" data-parsley-required="true"  >
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="country">@lang('app.country')</label>
                @if ($edit || $create)
                {!! Form::select('country', $countries, $edit ? $customer->country : old('country'), ['class' => 'form-control']) !!}
                @else
                <input type="text" disabled="disabled" id="county" value="{{$customer->countries()->first()->name}}" class="form-control" />
                @endif
            </div>            
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="city">@lang('app.city')</label>
                <input type="text" class="form-control" id="city"
                name="city" placeholder="@lang('app.city')" value="{{ $edit ? $customer->city : $view ? $customer->city : old('city') }}" {{ $view ? 'disabled' : ''}}>
            </div>           
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="address_1">@lang('app.address_1')</label>
                <input type="text" class="form-control" id="address_1"
                name="address_1" placeholder="@lang('app.address_1')" value="{{ $edit ? $customer->address_1 : $view ? $customer->address_1 : old('address_1') }}" {{ $view ? 'disabled' : ''}}>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="address_2">@lang('app.address_2')</label>
                <input type="text" class="form-control" id="address_2"
                name="address_2" placeholder="@lang('app.address_2')" value="{{ $edit ? $customer->address_2 : $view ? $customer->address_2 : old('address_2') }}" {{ $view ? 'disabled' : ''}}>
            </div>
        </div>


        @if(auth()->user()->getRole() != 1)
        <div class="col-md-4">
            <div class="form-group">
            <label for="country">@lang('app.branch')</label>            
            <select name="branch_id" class="form-control" data-plugin="newselect2" data-option="{}" >
                @if(!$edit)
                <option selected value="0"> -- please select one -- </option>
                @else
                <option value="0"> None </option>
                @endif
                @foreach($branches as $branch=>$value)                        
                    <option value="{{$branch}}"  <?php if($edit){if($branch == $customer->branch_id) { echo "selected";}}  ?> style="color:black;"> {{$value}} </option>                        
                @endforeach
            </select> 
            </div>            
        </div>
        @else
        <div class="col-md-4">
         <div class="form-group">
            <label for="country">@lang('app.branch')</label>            
            {{$customer->id}}
            <select name="branch_id" class="form-control" data-plugin="newselect2" data-option="{}"  disabled>            
                @foreach($customer->getBranches($customer->id) as $branch)                        
                {{$branch->title}}
                    <option value="{{$branch->id}}"  <?php if($edit){if($branch->id == $customer->branch_id) { echo "selected";}}  ?> style="color:black;"> {{$branch->title}} </option>                        
                @endforeach
            </select> 
        </div>      
        </div>
        @endif


    </div>

    <div class="row">
        <div class="col-md-12">
            @if ($edit)
            <button type="submit" class="btn btn-primary" id="update-details-btn">
                <i class="fa fa-refresh"></i>
                @lang('app.update_details')
            </button>
            <a href="{{ URL::previous() }}">
                <span class="btn btn-warning" id="update-details-btn">
                    <i class="fa fa-refresh"></i>
                    @lang('app.cancel')
                </span>
            </a>
            @endif
            @if ($view)
            <a href="{{ route('customer.edit', $customer->id) }}">
                <span class="btn btn-info" id="update-details-btn">
                    <i class="fa fa-edit"></i>
                    @lang('app.edit_customer')
                </span>
            </a>
            @endif
            
        </div>
    </div>
