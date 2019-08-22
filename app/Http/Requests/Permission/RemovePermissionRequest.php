<?php

namespace Tagydes\Http\Requests\Permission;

use Tagydes\Http\Requests\Request;

class RemovePermissionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('permission')->removable;
    }

    public function rules()
    {
        return [];
    }
}
