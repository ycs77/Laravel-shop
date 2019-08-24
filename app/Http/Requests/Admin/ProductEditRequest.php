<?php

namespace App\Http\Requests\Admin;

class ProductEditRequest extends ProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'sometimes|required|image',
        ] + parent::rules();
    }
}
