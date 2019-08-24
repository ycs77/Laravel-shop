@foreach ((array)$key as $errorKey)
  @if ($errors->has($errorKey))
    <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> {{ $errors->first($errorKey) }}</label><br/>
  @endif
@endforeach
