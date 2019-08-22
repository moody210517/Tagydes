<tr>
    <td style="width: 40px;">
        <a href="{{ route('user.show', $user->id) }}">
            <img
                class="rounded-circle img-responsive"
                width="40"
                src="{{ $user->present()->avatar }}"
                alt="{{ $user->present()->name }}">
        </a>
    </td>

    <td class="align-middle">
        <a href="{{ route('user.show', $user->id) }}">
            {{ $user->username ?: trans('app.n_a') }}
        </a>
    </td>

    <td class="align-middle">{{ $user->first_name . ' ' . $user->last_name }}</td>
    <td class="align-middle">{{ $user->email }}</td>

    
    @if($tab == 2)
    <?php $res = $user->resellerUser($user->associated_id); ?>    
    <td class="align-middle">  
    @if($res->first())
    <a href="{{ route('reseller.show', $res->first()->id ) }}">
    <?php echo $res->first()->company_name; ?> </a> 
    @endif
    </td>

    @elseif($tab == 3)
    <?php $res = $user->customerUser($user->associated_id); ?>
    @if($res->first())
    <td class="align-middle">  
    <a href="{{ route('customer.show', $res->first()->id ) }}">
    <?php echo $res->first()->company_name; ?> </a>  </a>     
    </td>
    @endif
    @endif

    <td class="align-middle">{{ $user->created_at->format(config('app.date_format')) }}</td>
    <td class="align-middle">
        <span class="badge badge-lg badge-{{ $user->present()->labelClass }}">
            {{ trans("app.{$user->status}") }}
        </span>
    </td>
    <td class="text-center align-middle">
        <div class="dropdown show d-inline-block">
        
            <a class="btn btn-icon"
               href="#" role="button" id="dropdownMenuLink"
               data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                @if (config('session.driver') == 'database')
                    <a href="{{ route('user.sessions', $user->id) }}" class="dropdown-item text-gray-500">
                        <i class="fas fa-list mr-2"></i>
                        @lang('app.user_sessions')
                    </a>
                @endif
                <a href="{{ route('user.show', $user->id) }}" class="dropdown-item text-gray-500">
                    <i class="fas fa-eye mr-2"></i>
                    @lang('app.view_user')
                </a>
                @canBeImpersonated($user)
                    <a href="{{ route('impersonate', $user->id) }}" class="dropdown-item text-gray-500 impersonate">
                        <i class="fas fa-user-secret mr-2"></i>
                        @lang('app.impersonate')
                    </a>
                @endCanBeImpersonated
            </div>

            
        </div>

        <a href="{{ route('user.edit', $user->id) }}"
           class="btn btn-icon edit"
           title="@lang('app.edit_user')"
           data-toggle="tooltip" data-placement="top">
            <i class="fas fa-edit"></i>
        </a>

        <a href="{{ route('user.delete', $user->id) }}"
           class="btn btn-icon"
           title="@lang('app.delete_user')"
           data-toggle="tooltip"
           data-placement="top"
           data-method="DELETE"
           data-confirm-title="@lang('app.please_confirm')"
           data-confirm-text="@lang('app.are_you_sure_delete_user')"
           data-confirm-delete="@lang('app.yes_delete_him')">
            <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>