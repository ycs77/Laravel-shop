@extends('layouts.app')

@section('title', $product->title)

@section('content')

  @component('component.card')
    <product-show inline-template>
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
            <div class="skus">
              <label>選擇</label>
              <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
                @foreach($product->skus as $sku)
                  <label class="btn btn-outline-primary sku-btn"
                      title="{{ $sku->description }}"
                      data-toggle="tooltip"
                      data-placement="bottom"
                      @click="skuSelected({{ $sku->price }}, {{ $sku->stock }})">
                    <input type="radio" name="skus" autocomplete="off" value="{{ $sku->id }}"> {{ $sku->title }}
                  </label>
                @endforeach
              </div>
            </div>
            <div class="cart_amount">
              <label>數量</label>
              <input type="number" class="form-control form-control-sm" value="1" min="0" max="9999">
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
            <div class="tab-pane fade show active" id="detail" role="tabpanel">
              {!! $product->description !!}
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel">
              用戶評價...
            </div>
          </div>
        </div>

      </div>
    </product-show>
  @endcomponent

@endsection

@push('script')
  <script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip({ trigger: 'hover' })

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
          sku_id: $('label.active input[name=skus]').val(),
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
