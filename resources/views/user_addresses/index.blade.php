@extends('layouts.app')

@section('title', __('user_address.list'))

@section('content')

  @card
    @slot('header')
      @lang('user_address.list')
      <a href="{{ route('user_addresses.create') }}">@lang('user_address.create')</a>
    @endslot

    <table class="table table-bordered mb-0">
      <thead>
        <tr class="text-center">
          <th>@lang('validation.attributes.contact_name')</th>
          <th>@lang('validation.attributes.address')</th>
          <th>@lang('validation.attributes.phone')</th>
          <th>@lang('validation.attributes.action')</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($user_address as $address)
          <tr>
            <td>{{ $address->contact_name }}</td>
            <td>{{ $address->full_address }}</td>
            <td>{{ $address->contact_phone }}</td>
            <td class="text-center">
              <a href="{{ route('user_addresses.edit', ['user_address' => $address->id]) }}" class="btn btn-primary">修改</a>
              <form action="{{ route('user_addresses.destroy', ['user_address' => $address->id]) }}" method="post" class="d-inline-block" onsubmit="return confirm('確認刪除該收件地址?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">刪除</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endcard

@endsection
