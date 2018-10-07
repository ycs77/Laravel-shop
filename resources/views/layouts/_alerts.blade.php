@if (isset($alerts) || session('alerts'))
  @php
    if (isset($alerts) && session('alerts')) {
      $alerts = array_collapse([$alerts, session('alerts')]);
    } else if (session('alerts')) {
      $alerts = session('alerts');
    }
  @endphp

  <script>
    @foreach ($alerts as $alert)
      showAlert('{{ $alert["msg"] }}', '{{ $alert["type"] ?? "success" }}')
    @endforeach
  </script>
@endif
