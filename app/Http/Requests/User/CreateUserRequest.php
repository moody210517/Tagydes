<?php

namespace Tagydes\Http\Requests\User;

use Tagydes\Http\Requests\Request;
use Tagydes\User;

class CreateUserRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'birthday' => 'nullable|date',
            
        ];

        if ($this->get('country_id')) {
            $rules += ['country_id' => 'exists:countries,id'];
        }

        if ($this->get('company_name')) {

            $rules += [
                'company_name' => 'required',
                'country' => 'required|not_in:0|exists:countries,id',
                

            ];
            if ($this->get('reseller')) {
                $rules += [
                    'nif' => 'required|min:5|unique:customer, id, reseller, ' . $this->get('reseller'),
                    'reseller' => 'required|not_in:0|existis:resellers,id',
                ];
            } else {
                $rules += [
                    'nif' => 'required|min:5|unique:resellers,nif',
                ];
            }

        } 
        if (!$this->get('company_name')) {
            $rules += [
                'role_id' => 'required|exists:roles,id',
            ];
        }


        return $rules;
    }
}
