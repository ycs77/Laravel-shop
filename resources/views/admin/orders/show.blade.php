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
        <td>
          <div>{{ $item->product->title }}</div>
          <div class="text-muted">
            @foreach ($item->productSku->attrs as $attr_name => $attr_item)
              <span class="mr-2"><b>{{ $attr_name }}: </b>{{ $attr_item }}</span>
            @endforeach
          </div>
        </td>
        <td>${{ $item->price }}</td>
        <td>{{ $item->amount }}</td>
      </tr>
      @endforeach

      <tr>
        <td>訂單金額：</td>
        <td>${{ $order->total_amount }}</td>
        <td>發貨狀態：</td>
        <td>{{ __("order.ship.{$order->ship_status}") }}</td>
      </tr>

      @if($order->ship_status === \App\Models\Order::SHIP_STATUS_PENDING)
        @if($order->refund_status !== \App\Models\Order::REFUND_STATUS_SUCCESS)
        <tr>
          <td colspan="4">
            <form action="{{ route('admin.orders.ship', [$order]) }}" method="post" class="form-inline">
              @csrf
              <div class="form-group {{ $errors->has('express_company') ? 'has-error' : '' }}">
                <label for="express_company" class="control-label">物流公司</label>
                <input type="text" id="express_company" name="express_company" value="" class="form-control" placeholder="輸入物流公司">
                @if($errors->has('express_company'))
                  @foreach($errors->get('express_company') as $msg)
                    <span class="help-block">{{ $msg }}</span>
                  @endforeach
                @endif
              </div>
              <div class="form-group {{ $errors->has('express_no') ? 'has-error' : '' }}">
                <label for="express_no" class="control-label">物流單號</label>
                <input type="text" id="express_no" name="express_no" value="" class="form-control" placeholder="輸入物流單號">
                @if($errors->has('express_no'))
                  @foreach($errors->get('express_no') as $msg)
                    <span class="help-block">{{ $msg }}</span>
                  @endforeach
                @endif
              </div>
              <button type="submit" class="btn btn-success" id="ship-btn">發貨</button>
            </form>
          </td>
        </tr>
        @endif
      @else
      <tr>
        <td>物流公司：</td>
        <td>{{ $order->ship_data['express_company'] }}</td>
        <td>物流單號：</td>
        <td>{{ $order->ship_data['express_no'] }}</td>
      </tr>
      @endif

      @if($order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)
        <tr>
          <td>退款狀態：</td>
          <td colspan="2">{{ __("order.refund.{$order->refund_status}") }}，理由：{{ $order->extra['refund_reason'] }}</td>
          <td>
            @if($order->refund_status === \App\Models\Order::REFUND_STATUS_APPLIED)
            <button class="btn btn-sm btn-success" id="btn-refund-agree">同意</button>
            <button class="btn btn-sm btn-danger" id="btn-refund-disagree">不同意</button>
            @endif
          </td>
        </tr>
      @endif
      </tbody>
    </table>
  </div>
</div>

<script>
$(function() {
  $('#btn-refund-agree').click(function () {
    swal({
      title: '確定要將款項退還給用戶?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: '確定',
      cancelButtonText: '取消',
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return $.ajax({
          url: '{{ route('admin.orders.handle_refund', $order) }}',
          type: 'POST',
          data: JSON.stringify({
            agree: true,
            _token: LA.token
          }),
          contentType: 'application/json'
        });
      }
    }).then(function (ret) {
      if (ret.dismiss === 'cancel') {
        return;
      }
      swal({
        title: '操作成功',
        type: 'success'
      }).then(function () {
        location.reload();
      });
    });
  })

  $('#btn-refund-disagree').click(function () {
    swal({
      title: '輸入拒絕退款裡由',
      input: 'text',
      showCancelButton: true,
      confirmButtonText: '確定',
      cancelButtonText: '取消',
      showLoaderOnConfirm: true,
      preConfirm: function (input) {
        if (!input) {
          swal('理由不能為空', '', 'error')
          return false;
        }

        return $.ajax({
          url: '{{ route('admin.orders.handle_refund', $order) }}',
          type: 'POST',
          data: JSON.stringify({
            agree: false,
            reason: inputValue,
            _token: LA.token
          }),
          contentType: 'application/json'
        });
      },
      allowOutsideClick: function () {
        return !swal.isLoading();
      }
    }).then(function (ret) {
      if (ret.dismiss === 'cancel') {
        return;
      }
      swal({
        title: '操作成功',
        type: 'success'
      }).then(function () {
        location.reload();
      });
    });
  })
})
</script>
