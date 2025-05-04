@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection
<x-black></x-black>

@section('main')
<div class="sell__page">
    <h1 class="sell__heading">商品の出品</h1>
    <form class="sell__form" action="{{ route('image_upload') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="sell__wrapper">
            <div class="sell__inner">
                <div class="sell__content">
                    <label for="img_url" class="label img">商品画像</label>
                    <div class="box">
                        <input type="file" class="img__input" name="img_url" id="img_url">
                        <label for="img_url" class="img__upload-btn">画像を選択する</label>
                    </div>
                    @error('img_url')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="sell__inner">
                <h2 class="sell__ttl">商品の詳細</h2>
                <div class="sell__content sell__content-under">
                    <label class="label">カテゴリー</label>
                    <input class="input" type="text" name="category">
                    @error('category')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="sell__content">
                    <label class="label">商品の状態</label>
                    <input class="input" type="text" name="condition">
                    @error('condition')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="sell__inner">
                <h2 class="sell__ttl">商品名と説明</h2>
                <div class="sell__content sell__content-under">
                    <label class="label">商品名</label>
                    <input class="input" type="text" name="name">
                    @error('name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="sell__content">
                    <label class="label">商品説明</label>
                    <textarea class="input input__textarea" name="description"></textarea>
                    @error('description')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="sell__inner">
                <h2 class="sell__ttl">販売価格</h2>
                <div class="sell__content sell__content-under">
                    <label class="label">販売価格</label>
                    <input class="input" type="text" name="price" placeholder="¥">
                    @error('price')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button class="sell__btn" type="submit">出品する</button>
        </div>
    </form>
</div>
@endsection