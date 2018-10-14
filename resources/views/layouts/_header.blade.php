<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="{{ route('root') }}">{{ config('app.name') }}</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav align-items-center ml-auto">
        @guest
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link" href="{{ route('cart.index') }}">購物車</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img src="{{ asset('svg/user.svg') }}" class="user-avatar rounded-circle">
              {{ Auth::user()->name ?? 'TEST' }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a href="{{ route('user_addresses.index') }}" class="dropdown-item">收件地址</a>
              <a href="{{ route('orders.index') }}" class="dropdown-item">我的訂單</a>
              <a href="{{ route('products.favorites') }}" class="dropdown-item">我的收藏</a>
              <div class="dropdown-divider"></div>
              <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                登出
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
