@extends('layouts.app')

@section('title', '購物車')

@section('content')

  @component('component.card', ['body' => false])
    @slot('header', '我的購物車')

    <table class="table mb-0">
      <thead>
        <tr>
          <th><input type="checkbox" id="select-all"></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody class="product_list">
        @foreach ($cartItems as $item)
          <tr data-id="{{ $item->productSku->id }}">
            <td>
              <input type="checkbox" name="select" value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? 'checked' : 'disabled' }}>
            </td>
            <td class="product_info">
              <div class="preview">
                <a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">
                  <img src="{{ $item->productSku->product->image_url }}">
                </a>
              </div>
              <div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
                <span class="product_title">
                  <a target="_blank" href="{{ route('products.show', [$item->productSku->product_id]) }}">{{ $item->productSku->product->title }}</a>
                </span>
                <span class="sku_title">{{ $item->productSku->title }}</span>
                @if(!$item->productSku->product->on_sale)
                  <span class="warning">該商品已下架</span>
                @endif
              </div>
            </td>
            <td><span class="price">${{ $item->productSku->price }}</span></td>
            <td>
              <input type="number" class="form-control form-control-sm amount" @if(!$item->productSku->product->on_sale) disabled @endif name="amount" value="{{ $item->amount }}">
            </td>
            <td>
              <button class="btn btn-sm btn-danger btn-remove">移除</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endcomponent

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
    })
  </script>
@endpush
