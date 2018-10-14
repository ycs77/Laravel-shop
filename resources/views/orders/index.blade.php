@extends('layouts.app')

@section('title', '訂單列表')

@section('content')

  @card
    @slot('header', '訂單列表')

    <ul class="list-group">
      @foreach ($orders as $order)
        <li class="list-group-item">
          @card(['body' => false, 'row' => false, 'sm' => false])
            @slot('header')
              訂單編號：{{ $order->no }}
              <span>{{ $order->created_at->format('Y/m/d H:i:s') }}</span>
            @endslot

            <table class="table mb-0">
              <thead>
                <tr class="text-center">
                  <th class="text-left">商品內容</th>
                  <th>單價</th>
                  <th>數量</th>
                  <th>總計</th>
                  <th>狀態</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->items as $index => $item)
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
                    <td class="sku-price text-center">${{ $item->price }}</td>
                    <td class="sku-amount text-center">{{ $item->amount }}</td>

                    @if($index === 0)
                    <td rowspan="{{ count($order->items) }}" class="text-center total-amount">
                      ${{ $order->total_amount }}
                    </td>
                    <td rowspan="{{ count($order->items) }}" class="text-center">
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
                        <br>
                        請於 {{ $order->created_at->addSeconds(config('app.order_ttl'))->format('Y/m/d H:i') }} 前完成付款<br>
                        否則訂單將自動關閉
                      @endif
                    </td>
                    <td rowspan="{{ count($order->items) }}" class="text-center">
                      <a class="btn btn-primary btn-xs" href="">查看訂單</a>
                    </td>
                    @endif
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endcard
        </li>
      @endforeach
    </ul>

    <div class="my-3">{{ $orders->links() }}</div>

  @endcard

@endsection
