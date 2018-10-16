<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">訂單流水號：{{ $order->no }}</h3>
    <div class="box-tools">
      <div class="btn-group pull-right" style="margin-right: 10px">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> 列表</a>
      </div>
    </div>
  </div>
  <div class="box-body">
    <table class="table table-bordered">
      <tbody>
      <tr>
        <td>買家：</td>
        <td>{{ $order->user->name }}</td>
        <td>支付時間：</td>
        <td>{{ $order->paid_at->format('Y-m-d H:i:s') }}</td>
      </tr>
      <tr>
        <td>支付方式：</td>
        <td>{{ $order->payment_method }}</td>
        <td>支付渠道單號：</td>
        <td>{{ $order->payment_no }}</td>
      </tr>
      <tr>
        <td>收貨地址：</td>
        <td colspan="3">{{ $order->address['address'] }} {{ $order->address['zip_code'] }} {{ $order->address['contact_name'] }} {{ $order->address['contact_phone'] }}</td>
      </tr>
      <tr>
        <td rowspan="{{ $order->items->count() + 1 }}">商品列表：</td>
        <td>商品名稱</td>
        <td>單價</td>
        <td>數量</td>
      </tr>
      @foreach($order->items as $item)
      <tr>
        <td>{{ $item->product->title }} {{ $item->productSku->title }}</td>
        <td>${{ $item->price }}</td>
        <td>{{ $item->amount }}</td>
      </tr>
      @endforeach
      <tr>
        <td>訂單金額：</td>
        <td colspan="3">${{ $order->total_amount }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
