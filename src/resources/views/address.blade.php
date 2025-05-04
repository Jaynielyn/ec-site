@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('main')
<x-black></x-black>
<div class="address__page">
    <div class="address__ttl">
        <h1 class="ttl">住所の変更</h1>
    </div>

    <form class="address__content" action="{{ route('purchase.updateAddress') }}" method="POST">
        @csrf
        <div class="inner">
            <label class="inner__label">
                <h2 class="inner__ttl">郵便番号</h2>
            </label>
            <input class="inner__input" type="text" name="postcode" id="postcode" value="{{ old('postcode', $profile->postcode ?? '') }}">
            @error('postcode')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="inner">
            <label class="inner__label">
                <h2 class="inner__ttl">住所</h2>
            </label>
            <input class="inner__input" type="text" name="address" id="address" value="{{ old('address', $profile->address ?? '') }}">
            @error('address')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="inner">
            <label class="inner__label">
                <h2 class="inner__ttl">建物</h2>
            </label>
            <input class="inner__input" type="text" name="building" id="building" value="{{ old('building', $profile->building ?? '') }}">
            @error('building')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn" type="submit">更新する</button>
    </form>

</div>
@endsection