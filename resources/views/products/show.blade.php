@extends('layouts.app')

@section('title', $product->title)

@section('content')

  @card
    <product-show :init-skus='@json($skus)' inline-template>
      <div class="product-info">

        <div class="row">
          {{-- 商品圖片 --}}
          <div class="col-md-5">
            <img class="cover" src="{{ $product->image_url }}" alt="">
          </div>

          {{-- 商品主要資訊 --}}
          <div class="col-md-7">
            <div class="title">{{ $product->title }}</div>
            <div class="price"><label>價格</label><em>$</em><span ref="price">{{ $product->price }}</span></div>
            <div class="sales_and_reviews">
              <div class="sold_count">累計銷量 <span class="count">{{ $product->sold_count }}</span></div>
              <div class="review_count">累計評價 <span class="count">{{ $product->review_count }}</span></div>
              <div class="rating" title="評分 {{ $product->rating }}">
                評分 <span class="count">{{ str_repeat('★', floor($product->rating)) }}{{ str_repeat('☆', 5 - floor($product->rating)) }}</span>
              </div>
            </div>

            <input type="hidden" name="sku" :value="sku ? sku.id : ''">
            @foreach($product->attrs as $attr_index => $attr)
            <div class="skus">
              <label>{{ $attr->name }}</label>
              <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                @foreach($attr->items as $item_index => $item)
                  <label class="btn btn-outline-primary" @click="skuSelected({{ $attr_index }}, {{ $item_index }})">
                    <input type="radio" autocomplete="off"> {{ $item }}
                  </label>
                @endforeach
              </div>
            </div>
            @endforeach

            <div class="cart_amount">
              <label>數量</label>
              <input type="number" class="form-control form-control-sm" value="1" min="0" :max="stock">
              <span>件</span>
              <span class="stock" v-if="showStock">庫存：@{{ stock }}件</span>
            </div>
            <div class="buttons">
              @if (!$favored)
                <button class="btn btn-success btn-faver">❤ 收藏</button>
              @else
                <button class="btn btn-danger btn-disfaver">取消收藏</button>
              @endif
              <button class="btn btn-primary btn-add-to-cart">加入購物車</button>
            </div>
          </div>
        </div>

        {{-- 商品詳細資訊 --}}
        <div class="product-detail">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a href="#detail" class="nav-link active" role="tab" data-toggle="tab" aria-controls="detail">商品介紹</a>
            </li>
            <li class="nav-item">
              <a href="#reviews" class="nav-link" role="tab" data-toggle="tab" aria-controls="reviews">用戶評價</a>
            </li>
          </ul>
          <div class="tab-content">

            {{-- 商品介紹 --}}
            <div class="tab-pane fade show active" id="detail" role="tabpanel">
              {!! $product->description !!}
            </div>

            {{-- 用戶評價 --}}
            <div class="tab-pane fade" id="reviews" role="tabpanel">
              @foreach($reviews as $review)
              <div class="media mb-3">
                <img src="{{ asset('svg/user.svg') }}" class="user-avatar rounded-circle mr-3">
                <div class="media-body">
                  <div class="text-muted">
                    <small>{{ $review->order->user->name }} · {{ $review->reviewed_at->diffForHumans(now()) }}</small>
                  </div>
                  <div>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                  <div class="my-1">{{ $review->review }}</div>
                  <div class="text-muted">
                    <small>
                      規格：
                      @foreach ($review->productSku->attrs as $attr_name => $attr_item)
                        <span class="mr-2">{{ $attr_name }}: {{ $attr_item }}</span>
                      @endforeach
                    </small>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>

      </div>
    </product-show>
  @endcard

@endsection

@push('script')
  <script>
    $(function () {
      $('.btn-faver').click(function () {
        axios.post('{{ route('products.favor', ['product' => $product->id]) }}').then(function () {
          swal('成功加入收藏', '', 'success').then(function () {
            location.reload()
          })
        }).catch(function (error) {
          if (error.response.status === 401) {
            swal('請先登入', '', 'error').then(function () {
              location.href = '{{ route('login') }}'
            })
          } else if (error.response.data.msg) {
            swal(error.response.data.msg, '', 'error')
          } else {
            swal('系統錯誤', '', 'error')
          }
        })
      })

      $('.btn-disfaver').click(function () {
        axios.delete('{{ route('products.disfavor', ['product' => $product->id]) }}').then(function () {
          swal('成功取消收藏', '', 'success').then(function () {
            location.reload()
          })
        })
      })

      $('.btn-add-to-cart').click(function () {
        axios.post('{{ route('cart.add') }}', {
          sku_id: $('input[name=sku]').val(),
          amount: $('.cart_amount input').val()
        }).then(function () {
          swal('成功加入購物車', '', 'success').then(function () {
            location.href = '{{ route('cart.index') }}'
          })
        }).catch(function (error) {
          if (error.response.status === 401) {
            swal('請先登入', '', 'error').then(function () {
              location.href = '{{ route('login') }}'
            })
          } else if (error.response.status === 422) {
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
