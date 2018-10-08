@extends('layouts.app')

@section('title', __('user_address.list'))

@section('content')
  <div class="row">
    <div class="col-lg-10 offset-lg-1">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          @lang('user_address.list')
          <a href="{{ route('user_addresses.create') }}">@lang('user_address.create')</a>
        </div>
        <table class="table mb-0">
          <thead>
            <tr>
              <th>@lang('validation.attributes.contact_name')</th>
              <th>@lang('validation.attributes.address')</th>
              <th>@lang('validation.attributes.phone')</th>
              <th>@lang('validation.attributes.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($addresses as $address)
              <tr>
                <td>{{ $address->contact_name }}</td>
                <td>{{ $address->full_address }}</td>
                <td>{{ $address->contact_phone }}</td>
                <td>
                  <button class="btn btn-primary">修改</button>
                  <button class="btn btn-danger">刪除</button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
