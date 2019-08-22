

<tr>
    <td class="text-nowrap">
        <a href="{{ route('reseller.show', $reseller->id) }}">
            {{ $reseller->company_name }}
        </a>
    </td>
    
    {{-- <td class="align-middle">
        <a href="{{ route('reseller.show', $reseller->id) }}">
            {{ $reseller->address_1 }}
        </a>
    </td> --}}


    <td class="odd gradeC">
        <a href="" class="dropdown-item text-gray-500">
            
            {{ $reseller->companies()->count()  }}
        </a>
    </td>

    <td class="align-middle">{{ $reseller->getBranchName($reseller->branch_id) }} </td> 

    <td class="align-middle">{{ $reseller->countries->name }}
    </td>
    <td class="align-middle">
        <span class="badge badge-lg {{ $reseller->isActive() ? 'badge-success' : 'badge-danger' }}">

            {{ trans("app.{$reseller->status}") }}
        </span>
    </td>
    <td class="text-center align-middle text-light">
        <div class="dropdown show d-inline-block">
            <a class="btn btn-icon text-info" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                    @if(empty($reseller->main_office))
                    <a href="{{ route('reseller.branch', ['reseller' => $reseller->id]) }}" class="dropdown-item text-gray-500">
                        <i class="fas fa-eye mr-2"></i>
                        @lang('app.view_branch_offices')
                    </a>
                    @endif

                    <a href="{{ route('reseller.user', ['reseller' => $reseller->id]) }}" class="dropdown-item text-gray-500">
                        <i class="fas fa-eye mr-2"></i>
                        @lang('app.view_users')
                    </a>

                    @canBeImpersonated($reseller->getMainUser())
                    <a href="{{ route('impersonate', $reseller->getMainUser()->id) }}" class="dropdown-item text-gray-500 impersonate">
                        <i class="fas fa-user-secret mr-2"></i>                    
                        @lang('app.impersonate')
                    </a>
                    @endCanBeImpersonated
                
            </div>
        </div>

        <a href="{{ route('reseller.edit', $reseller->id) }}" class="btn btn-icon edit text-warning" title="@lang('app.edit_reseller')" data-toggle="tooltip" data-placement="top">
            <i class="fas fa-edit"></i>
        </a>

        <a href="{{ route('reseller.status', $reseller->id) }}" class="btn btn-icon {{ $reseller->isActive() ? 'text-grey' : 'text-success' }}" title="{{ $reseller->isActive() ? trans('app.disable_reseller') : trans('app.enable_reseller') }}" data-toggle="tooltip" data-placement="top" data-method="{{ $reseller->isActive() ? trans('app.disable') : trans('app.enable') }}" data-confirm-title="@lang('app.please_confirm')" data-confirm-text="{{ $reseller->isActive() ? trans('app.are_you_sure_disable_reseller') : trans('app.are_you_sure_enable_reseller') }}" data-confirm-delete="{{ $reseller->isActive() ? trans('app.yes_disable_reseller') : trans('app.yes_enable_reseller') }}">
            <i class="fas fa-{{ $reseller->isActive() ? 'eye-slash' : 'eye' }}"></i>



        </a>


        @if(empty($reseller->main_office))
        @permission('add_branch_office')
        <a href="#" class="btn btn-icon edit text-light" title="@lang('app.add_branch_office')" data-toggle="tooltip" data-placement="top">
            <i class="fas fa-building"></i>
        </a>
        @endpermission
        @endif


    </td>
</tr>