@extends('layouts.app')

@section('title', '首頁')

@section('content')
  <h1>這是首頁</h1>

  @auth
    @empty(request()->user()->hasVerifiedEmail())
      <a href="{{ route('verification.notice') }}">驗證E-mail</a>
    @endempty
  @endauth
@endsection
