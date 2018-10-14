@extends('layouts.app')

@section('title', __('user_address.' . (!$address->id ? 'create' : 'edit')))

@section('content')

  @card
    @slot('header', __('user_address.' . (!$address->id ? 'create' : 'edit')))

      {{-- 錯誤訊息開始 --}}
      @if (count($errors))
      @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
      @endforeach
      @endif
      {{-- 錯誤訊息結束 --}}

      <user-addresses-create-and-edit init-address="{{ old('address', $address->address) }}" inline-template>
        <form action="{{ !$address->id ? route('user_addresses.store') : route('user_addresses.update', ['user_address' => $address->id]) }}" method="post">
          @if ($address->id)
            @method('PUT')
          @endif
          @csrf

          <select-district @change="onDistrictChanged" :init-value="['{{ old('city', $address->city) }}', '{{ old('district', $address->district) }}']" inline-template>
            <div class="form-row">
              <div class="form-group col-6">
                <label>縣市</label>
                <select class="form-control" v-model="cityId">
                  <option value="">請選擇縣市</option>
                  <option v-for="(city, index) in cities" :key="index" :value="index">@{{ city }}</option>
                </select>
              </div>
              <div class="form-group col-6">
                <label>鄉鎮市區</label>
                <select class="form-control" v-model="districtId">
                  <option value="">請選擇鄉鎮市區</option>
                  <option v-for="(district, index) in districts" :key="index" :value="index">@{{ district }}</option>
                </select>
              </div>
            </div>
          </select-district>

          <input type="hidden" name="city" v-model="city">
          <input type="hidden" name="district" v-model="district">
          <input type="hidden" name="zip_code" v-model="zip_code">

          <div class="form-group">
            <label>@lang('validation.attributes.address')</label>
            <input type="text" class="form-control" name="address" v-model="address">
          </div>

          <div class="form-group">
            <label>@lang('validation.attributes.full_address')</label>
            <div>@{{ fullAddress || '無' }}</div>
          </div>

          <div class="form-group">
            <label>@lang('validation.attributes.contact_name')</label>
            <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name', $address->contact_name) }}">
          </div>

          <div class="form-group">
            <label>@lang('validation.attributes.contact_phone')</label>
            <input type="text" class="form-control" name="contact_phone" value="{{ old('contact_phone', $address->contact_phone) }}">
          </div>

          <div class="form-group text-center">
            <button class="btn btn-primary">送出</button>
          </div>

        </form>
      </user-addresses-create-and-edit>

  @endcard

@endsection
