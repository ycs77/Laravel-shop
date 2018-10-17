<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class HandleRefundRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'agree' => 'required|boolean',
            'reason' => 'required_if:agree,false', // 拒絕退款時需要輸入拒絕理由
        ];
    }
}
