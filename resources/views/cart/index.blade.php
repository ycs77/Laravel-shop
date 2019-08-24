@extends('layouts.app')

@section('title', '購物車')

@section('content')

  @card
    @slot('header', '我的購物車')

    <table class="table table-bordered mb-0">
      <thead>
        <tr class="text-center">
          <th><input type="checkbox" id="select-all"></th>
          <th>商品內容</th>
          <th>單價</th>
          <th>數量</th>
          <th>編輯</th>
        </tr>
      </thead>
      <tbody class="product_list">
        @forelse ($cartItems as $item)
          <tr data-id="{{ $item->productSku->id }}">
            <td>
              <input type="checkbox" name="select" value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
            </td>
            <td class="product_info">
              <div class="preview">
                <a target="_blank" href="{{ route('products.show', [$item->productSku->product]) }}">
                  <img src="{{ $item->productSku->product->image_url }}">
                </a>
              </div>
              <div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
                <span class="product_title">
                  <a target="_blank" href="{{ route('products.show', [$item->productSku->product]) }}">{{ $item->productSku->product->title }}</a>
                </span>
                <span class="sku_title">
                  @foreach ($item->productSku->attrs as $attr_name => $attr_item)
                    <span class="mr-2"><b>{{ $attr_name }}: </b>{{ $attr_item }}</span>
                  @endforeach
                </span>
                @if(!$item->productSku->product->on_sale)
                  <span class="warning">該商品已下架</span>
                @endif
              </div>
            </td>
            <td><span class="price">${{ $item->productSku->price }}</span></td>
            <td>
              <input type="number" class="form-control form-control-sm amount" @if(!$item->productSku->product->on_sale) disabled @endif name="amount" value="{{ $item->amount }}">
            </td>
            <td class="text-center">
              <button class="btn btn-sm btn-danger btn-remove">移除</button>
            </td>
          </tr>
        @empty
          <tr>
            <td class="text-center text-muted" colspan="5">
              現在購物車中沒有任何商品，馬上去購物吧！
              <a href="{{ route('products.index') }}" class="btn btn-outline-primary">商品區</a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="p-3">
      <form role="form" id="order-form">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">選擇收件地址</label>
          <div class="col-sm-10">
            <select name="address" class="form-control">
              @foreach($addresses as $address)
                <option value="{{ $address->id }}">{{ $address->full_address }} {{ $address->contact_name }} {{ $address->contact_phone }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label">備注</label>
          <div class="col-sm-10">
            <textarea type="text" name="remark" class="form-control" rows="3"></textarea>
          </div>
        </div>

        <div class="form-group text-center">
          <button type="button" class="btn btn-primary btn-create-order">送出訂單</button>
        </div>
      </form>
    </div>
  @endcard

@endsection

@push('script')
  <script>
    $(function () {
      $('.btn-remove').click(function () {
        var id = $(this).closest('tr').data('id')
        swal({
          title: '確認將該商品移除?',
          icon: 'warning',
          buttons: ['取消', '確定'],
          dangerMode: true
        }).then(function (willDelete) {
          if (!willDelete) return
          axios.delete('/cart/' + id).then(function () {
            location.reload()
          })
        })
      })

      $('#select-all').change(function() {
        var checked = $(this).prop('checked')
        $('input[name=select][type=checkbox]:not([disabled])').each(function() {
          $(this).prop('checked', checked)
        })
      })

      $('.btn-create-order').click(function () {
        var data = {
          address_id: $('#order-form select[name=address]').val(),
          items: [],
          remark: $('#order-form textarea[name=remark]').val()
        }
        $('table tr[data-id]').each(function () {
          var $checkbox = $(this).find('input[name=select][type=checkbox]')
          if ($checkbox.prop('disabled') || !$checkbox.prop('checked')) return
          var $input = $(this).find('input[name=amount]')
          if ($input.val() <= 0 || isNaN($input.val())) return
          data.items.push({
            sku_id: $(this).data('id'),
            amount: $input.val(),
          })
        })
        axios.post('{{ route('orders.store') }}', data).then(function (res) {
          swal('訂單提交成功', '', 'success').then(function () {
            location.href = '/orders/' + res.data.id
          })
        }).catch(function (error) {
          if (error.response.status === 422) {
            var html = ''
            var ers = error.response.data.errors
            Object.keys(ers).forEach(function (key) {
              ers[key].forEach(function (error) {
                html += error + '<br>'
              })
            })
            html = '<div>' + html.replace(/<br>$/, '') + '</div>'
            swal({ content: $(html).get(0), icon: 'error' })
          } else {
            swal('系統錯誤', '', 'error')
          }
        })
      })
    })
  </script>
@endpush
