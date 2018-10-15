@extends('layouts.app')

@section('title', '付款')

@section('content')

  @card
    @slot('header', $subject)

    <div class="h4">總計：${{ $total_amount }}</div>

    <form action="{{ route('payment.website.notify') }}" method="post">

      <input type="hidden" name="out_trade_no" value="{{ $out_trade_no }}">
      <input type="hidden" name="trade_no" value="{{ $out_trade_no }}">

      <div class="mt-5 text-center">
        <button class="btn btn-primary">付款</button>
      </div>
    </form>
  @endcard

@endsection
