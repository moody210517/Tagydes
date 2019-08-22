<?php

namespace Tagydes\Http\Requests\User;

use Tagydes\Http\Requests\Request;
use Tagydes\User;

class UpdateProfileDetailsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'birthday' => 'nullable|date',
        ];
    }
}
