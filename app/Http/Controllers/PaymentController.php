<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exceptions\InvalidRequestException;

class PaymentController extends Controller
{
    public function payByWebsite(Order $order)
    {
        $this->authorize('own', $order);

        if ($order->paid_at || $order->closed) {
            throw new InvalidRequestException('無法付款');
        }

        return view('pay.website', [
            'out_trade_no' => $order->no,
            'total_amount' => $order->total_amount,
            'subject'      => '支付 Laravel Shop 的訂單：' . $order->no,
        ]);
    }

    public function websiteNotify(Request $request)
    {
        $data = $request->all();

        $order = order::where('no', $data['out_trade_no'])->first();

        if (!$order) return;
        if ($order->paid_at) {
            return redirect()->route('orders.show', [$order]);
        }

        $order->update([
            'paid_at'        => Carbon::now(),
            'payment_method' => 'website',
            'payment_no'     => $data['trade_no'],
        ]);

        return redirect()->route('orders.show', [$order]);
    }
}
