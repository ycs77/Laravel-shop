<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Simulated payment page.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Payment notify.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function websiteNotify(Request $request)
    {
        $data = $request->all();

        if (!$order = order::where('no', $data['out_trade_no'])->first()) {
            return;
        }

        if ($order->paid_at) {
            return redirect()->route('orders.show', [$order]);
        }

        $order->update([
            'paid_at'        => now(),
            'payment_method' => 'website',
            'payment_no'     => $data['trade_no'],
        ]);

        $this->afterPaid($order);

        return redirect()->route('orders.show', [$order]);
    }

    /**
     * The after paid called.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    protected function afterPaid(Order $order)
    {
        event(new OrderPaid($order));
    }
}
