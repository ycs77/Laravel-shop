<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name')) - Laravel 商城</title>

    {{-- 樣式 --}}
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    @stack('style')
  </head>
  <body>
    <div id="app" class="{{ route_class() }}-page">
      @include('layouts._header')

      <div id="alert-block"></div>

      <div class="container my-5">
        @yield('content')
      </div>

      @include('layouts._footer')
    </div>

    {{-- JS腳本 --}}
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    @stack('script')

    @include('layouts._alerts')

  </body>
</html>
