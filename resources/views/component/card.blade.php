<div class="row">
  <div class="col-lg-10 offset-lg-1">
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
</div>
