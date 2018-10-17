@extends('layouts.app')

@section('title', '商品評價')

@section('content')

  @card(['body' => false])
    @slot('header')
      商品評價
      <a href="{{ route('orders.index') }}">返回訂單列表</a>
    @endslot

    <form action="{{ route('orders.review.store', $order) }}" method="post">
      @csrf

      <table class="table mb-0">
        <thead>
          <tr>
            <th>商品名稱</th>
            <th>評分</th>
            <th>評價</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $index => $item)
          <tr>

            {{-- 商品名稱 --}}
            <td class="product-info">
              <div class="preview">
                <a target="_blank" href="{{ route('products.show', $item->product) }}">
                  <img src="{{ $item->product->image_url }}">
                </a>
              </div>
              <div>
                <span class="product-title">
                  <a target="_blank" href="{{ route('products.show', $item->product) }}">
                    {{ $item->product->title }}
                  </a>
                </span>
                <span class="sku-title">{{ $item->productSku->title }}</span>
              </div>
              <input type="hidden" name="reviews[{{ $index }}][id]" value="{{ $item->id }}">
            </td>

            {{-- 評分 --}}
            <td class="align-middle">
              @if($order->reviewed)
              <span class="rating-star-yes">{{ str_repeat('★', $item->rating) }}</span><span class="rating-star-no">{{ str_repeat('★', 5 - $item->rating) }}</span>
              @else
              <ul class="rate-area">
                @for ($i = 5; $i >= 1; $i--)
                <input type="radio" id="{{ $i }}-star-{{ $index }}" name="reviews[{{ $index }}][rating]" value="{{ $i }}" {{ $i == 5 ? 'checked' : '' }} />
                <label for="{{ $i }}-star-{{ $index }}"></label>
                @endfor
              </ul>
              @endif
            </td>

            {{-- 評價 --}}
            <td class="{{ $errors->has('reviews.'.$index.'.review') ? 'has-error' : '' }}">
              @if($order->reviewed)
                {{ $item->review }}
              @else
                <textarea class="form-control @if($errors->has("reviews.{$index}.review")) is-invalid @endif" name="reviews[{{ $index }}][review]"></textarea>
                @if($errors->has("reviews.{$index}.review"))
                  <span class="invalid-feedback">{{ $errors->first("reviews.{$index}.review") }}</span>
                @endif
              @endif
            </td>

          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-center">
              @if(!$order->reviewed)
              <button type="submit" class="btn btn-primary center-block">提交</button>
              @else
              <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">查看訂單</a>
              @endif
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  @endcard

@endsection
