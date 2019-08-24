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
              <a target="_blank" href="{{ route('products.show', [$item->product]) }}">
                <img src="{{ $item->product->image_url }}">
              </a>
            </div>
            <div>
              <div class="product-title">
                <a target="_blank" href="{{ route('products.show', [$item->product]) }}">{{ $item->product->title }}</a>
              </div>
              <small class="sku-title">
                @foreach ($item->productSku->attrs as $attr_name => $attr_item)
                  <span class="mr-2"><b>{{ $attr_name }}: </b>{{ $attr_item }}</span>
                @endforeach
              </small>
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

        <div class="line">
          <div class="line-label">物流狀態：</div>
          <div class="line-value">{{ __("order.ship.{$order->ship_status}") }}</div>
        </div>
        @if($order->ship_data)
        <div class="line">
          <div class="line-label">物流信息：</div>
          <div class="line-value">{{ $order->ship_data['express_company'] }} {{ $order->ship_data['express_no'] }}</div>
        </div>
        @endif

        @if($order->paid_at && $order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)
        <div class="line">
          <div class="line-label">退款狀態：</div>
          <div class="line-value">{{ __("order.refund.{$order->refund_status}") }}</div>
        </div>
        <div class="line">
          <div class="line-label">退款理由：</div>
          <div class="line-value">{{ $order->extra['refund_reason'] }}</div>
        </div>
        @endif

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

          @if(isset($order->extra['refund_disagree_reason']))
          <div>
            <span>拒絕退款理由：</span>
            <div class="value">{{ $order->extra['refund_disagree_reason'] }}</div>
          </div>
          @endif
        </div>

        @if(!$order->paid_at && !$order->closed)
        <div class="my-3 pr-4">
          <a class="btn btn-primary btn-sm" href="{{ route('payment.website', [$order]) }}">
            付款
          </a>
        </div>
        @endif

        @if($order->ship_status === \App\Models\Order::SHIP_STATUS_DELIVERED)
        <div class="my-3 pr-4">
          <form method="post" action="{{ route('orders.received', [$order]) }}" onsubmit="return confirm('確認已收到商品?')">
            @csrf
            <button class="btn btn-sm btn-success">確認收貨</button>
          </form>
        </div>
        @endif

        @if($order->paid_at && $order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
        <div class="my-3 pr-4">
          @if ($order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
          <button class="btn btn-sm btn-danger mr-1" id="btn-apply-refund">申請退款</button>
          @endif

          <a class="btn btn-success btn-sm" href="{{ route('orders.review.show', $order) }}">
            {{ $order->reviewed ? '查看評價' : '評價' }}
          </a>
        </div>
        @endif

      </div>
    </div>
  @endcard

@endsection

@push('script')
  <script>
    $(function () {
      $('#btn-apply-refund').click(function () {
        swal({
          text: '請輸入退款理由',
          content: 'input'
        }).then(function (input) {
          if(!input) {
            swal('退款理由不可空', '', 'error');
            return;
          }

          axios.post('{{ route('orders.apply_refund', $order) }}', { reason: input }).then(function () {
            swal('申請退款成功', '', 'success').then(function () {
              location.reload();
            });
          })
        })
      })
    })
  </script>
@endpush
