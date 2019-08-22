<?php

namespace Tagydes\Http\Requests\Customer;

use Tagydes\Http\Requests\Request;
use Tagydes\Customer;

class UpdateDetailsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'company_name' => 'required',
            'nif' => 'required|min:5',
            'country' => 'required|not_in:0|exists:countries,id',
        ];

        return $rules;
    }
}
