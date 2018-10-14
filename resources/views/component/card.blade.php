@if ($row ?? true)<div class="row">@endif
  <div @if ($sm ?? true) class="col-lg-10 offset-lg-1" @endif>
    <div class="card">
      @if (isset($header) && $header)
        <div class="card-header d-flex justify-content-between">
          {{ $header }}
        </div>
      @endif
      @if ($body ?? true)
        <div class="card-body">{{ $slot }}</div>
      @else
        {{ $slot }}
      @endif
    </div>
  </div>
@if ($row ?? true)</div>@endif
