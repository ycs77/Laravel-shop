@if (isset($alerts) || session('alerts'))
  @php
    if (isset($alerts) && session('alerts')) {
      $alerts = array_collapse([$alerts, session('alerts')]);
    } else if (session('alerts')) {
      $alerts = session('alerts');
    }
  @endphp

  <script>
    @if (is_string($alerts))
      showAlert('{{ $alerts }}')
    @else
      @foreach ($alerts as $alert)
        showAlert('{{ is_string($alert) ? $alert : $alert["msg"] }}', '{{ $alert["type"] ?? "success" }}')
      @endforeach
    @endif
  </script>
@endif
