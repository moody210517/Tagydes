

<tr>
    <td class="text-nowrap">
        <a href="{{ route('customer.show', $customer->id) }}">
            {{ $customer->company_name }}
        </a>
    </td>
    <td class="align-middle">
        <a href="{{ route('reseller.show', $customer->reseller) }}">
            {{ $customer->resellers->company_name }}
        </a>
    </td>
    <td class="align-middle">{{ $customer->getBranchName($customer->branch_id) }}</td>
    <td class="align-middle">{{ $customer->countries->name }}
    </td>
    <td class="align-middle">
        <span class="badge badge-lg">

            {{ trans("app.{$customer->status}") }}
        </span>
    </td>
    <td class="text-center align-middle">
        <div class="dropdown show d-inline-block">
            <a class="btn btn-icon" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">


                <a href="{{ route('customer.user', ['customer' => $customer->id]) }}" class="dropdown-item text-gray-500">
                    <i class="fas fa-eye mr-2"></i>
                    @lang('app.view_users')
                </a>

                @canBeImpersonated($customer->getMainUser())
                @if($customer->isActive())
                <a href="{{ route('impersonate', $customer->getMainUser()->id) }}" class="dropdown-item text-gray-500 impersonate">
                    <i class="fas fa-user-secret mr-2"></i>
                    @lang('app.impersonate')
                </a>
                @endif
                @endCanBeImpersonated
            </div>
        </div>

        <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-icon edit" title="@lang('app.edit_customer')" data-toggle="tooltip" data-placement="top">
            <i class="fas fa-edit"></i>
        </a>

        <a href="{{ route('customer.delete', $customer->id) }}" class="btn btn-icon" title="@lang('app.delete_customer')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('app.please_confirm')" data-confirm-text="@lang('app.are_you_sure_delete_customer')" data-confirm-delete="@lang('app.yes_delete_him')">
            <i class="fas fa-trash"></i>
        </a>


    </td>
</tr>