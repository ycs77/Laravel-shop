@extends('layouts.app')

@section('title', '商品列表')

@section('content')

  @component('component.card')
    @slot('header', '商品列表')
    
    <div class="row products-list">
      @foreach($products as $product)
        <div class="col-sm-3 product-item">
          <div class="product-content">
            <div class="top">
              <div class="img"><img src="{{ $product->image_url }}" alt=""></div>
              <div class="price"><b>$</b>{{ $product->price }}</div>
              <div class="title">{{ $product->title }}</div>
            </div>
            <div class="bottom">
              <div class="sold_count">銷量 <span>{{ $product->sold_count }}筆</span></div>
              <div class="review_count">評價 <span>{{ $product->review_count }}</span></div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    
    <div class="my-3">{{ $products->render() }}</div>
  @endcomponent

@endsection
