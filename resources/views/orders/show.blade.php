@extends('layouts.app')

@section('title', '訂單詳情')

@section('content')

  @card(['body' => false])
    @slot('header', '訂單詳情')
    
    <table class="table mb-0">
      <thead>
        <tr class="text-center">
          <th class="text-left">商品內容</th>
          <th>單價</th>
          <th>數量</th>
          <th class="text-right item-amount">小計</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $index => $item)
        <tr>
          <td class="product-info">
            <div class="preview">
              <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">
                <img src="{{ $item->product->image_url }}">
              </a>
            </div>
            <div>
              <span class="product-title">
                <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">{{ $item->product->title }}</a>
              </span>
              <span class="sku-title">{{ $item->productSku->title }}</span>
            </div>
          </td>
          <td class="sku-price text-center align-middle">${{ $item->price }}</td>
          <td class="sku-amount text-center align-middle">{{ $item->amount }}</td>
          <td class="item-amount text-right align-middle">${{ number_format($item->price * $item->amount, 2, '.', '') }}</td>
        </tr>
        @endforeach
        <tr><td colspan="4"></td></tr>
      </tbody>
    </table>

    <div class="row p-3">
      <div class="col-sm order-info">
        <div class="line">
          <div class="line-label">收貨地址：</div>
          <div class="line-value">{{ join(' ', $order->address) }}</div>
        </div>
        <div class="line">
          <div class="line-label">訂單備註：</div>
          <div class="line-value">{{ $order->remark ?: '-' }}</div>
        </div>
        <div class="line">
          <div class="line-label">訂單編號：</div>
          <div class="line-value">{{ $order->no }}</div>
        </div>
      </div>
      <div class="col-sm text-right">
        <div class="total-amount">
          <span>訂單總價：</span>
          <div class="value pr-4">${{ $order->total_amount }}</div>
        </div>
        <div>
          <span>訂單狀態：</span>
          <div class="value pr-4">
            @if($order->paid_at)
              <span class="badge badge-{{ $order->refund_status_color }}">
                @if($order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
                  已付款
                @else
                  {{ __("order.refund.{$order->refund_status}") }}
                @endif
              </span>
            @elseif($order->closed)
              <span class="badge badge-info">已關閉</span>
            @else
              <span class="badge badge-danger">未付款</span>
            @endif
          </div>
        </div>

        @if(!$order->paid_at && !$order->closed)
        <div class="my-3 pr-4">
          <a class="btn btn-primary btn-sm" href="{{ route('payment.website', ['order' => $order->id]) }}">
            付款
          </a>
        </div>
        @endif
      </div>
    </div>
  @endcard

@endsection
