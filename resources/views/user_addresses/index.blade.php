@extends('layouts.app')

@section('title', '收件地址列表')

@section('content')
  <div class="row">
    <div class="col-lg-10 offset-lg-1">
      <div class="card">
        <div class="card-header">收件地址列表</div>
        <table class="table">
          <thead>
            <tr>
              <th>收件人</th>
              <th>地址</th>
              <th>電話</th>
              <th>編輯</th>
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
