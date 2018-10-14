@extends('layouts.app')

@section('title', '商品列表')

@section('content')

  @card
    @slot('header', '商品列表')

    {{-- 搜尋 篩選 --}}
    <div class="row">
      <div class="col">
        <search-bar :filters='{!! json_encode($filters) !!}' inline-template>
          <form action="{{ route('products.index') }}" class="form-inline serach-form" ref="serachForm">
            <div class="input-group input-group-sm">
              <input type="text" class="form-control" name="search" placeholder="搜尋" requied v-model="search">
              <div class="input-group-append">
                <button class="btn btn-outline-primary">搜尋</button>
              </div>
            </div>

            <select class="form-control form-control-sm" name="orderby" v-model="orderby" @change="submitSearchForm">
              <option value="">排序方式</option>
              <option value="price_asc">價格由低到高</option>
              <option value="price_desc">價格由高到低</option>
              <option value="sold_count_asc">銷量由低到高</option>
              <option value="sold_count_desc">銷量由高到低</option>
              <option value="rating_asc">評價由低到高</option>
              <option value="rating_desc">評價由高到低</option>
            </select>
          </form>
        </search-bar>
      </div>
    </div>
    
    {{-- 商品列表 --}}
    <div class="row products-list">
      @foreach($products as $product)
        <div class="col-sm-3 product-item">
          <div class="product-content">
            <div class="top">
              <div class="img">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                  <img src="{{ $product->image_url }}" alt="">
                </a>
              </div>
              <div class="price"><b>$</b>{{ $product->price }}</div>
              <div class="title">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                  {{ $product->title }}
                </a>
              </div>
            </div>
            <div class="bottom">
              <div class="sold_count">銷量 <span>{{ $product->sold_count }}筆</span></div>
              <div class="review_count">評價 <span>{{ $product->review_count }}</span></div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    
    <div class="my-3">{{ $products->appends($filters)->links() }}</div>
  @endcard

@endsection
