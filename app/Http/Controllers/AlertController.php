<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlertController extends Controller
{
    private function messages()
    {
        return [
            [
                'name' => 'successPasswordReset',
                'route' => 'root',
                'message' => __('Successful password reset'),
            ],
            [
                'name' => 'sendVerifyMail',
                'route' => 'root',
                'message' => __('A fresh verification link has been sent to your email address.'),
            ],
            [
                'name' => 'successVerify',
                'route' => 'root',
                'message' => __('Successful verification'),
            ],
        ];
    }

    private function to($routeName, $msg)
    {
        $alerts = is_string($msg)
            ? [['msg' => $msg]]
            : $msg;
        return redirect()->route($routeName)->with('alerts', $alerts);
    }

    public function handle(Request $request, $route, $name)
    {
        $messages = $this->messages();
        $message = array_filter($messages, function ($v) use ($route, $name) {
            return $v['route'] == $route && $v['name'] == $name;
        });
        $message = array_divide($message)[1];
        if (isset($message[0])) {
            return $this->to($message[0]['route'], $message[0]['message']);
        }
        abort(404);
    }
}
