<?php

namespace App\Http\Requests;

class UserAddressRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'city'          => 'required',
            'district'      => 'required',
            'address'       => 'required',
            'contact_name'  => 'required',
            'contact_phone' => 'required',
        ];
    }
}
