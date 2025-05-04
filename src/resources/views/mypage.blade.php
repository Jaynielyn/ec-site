@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection
<x-header></x-header>

@section('main')
<div class="mypage__page">
    <div class="mypage__top">
        <div class="mypage__details">
            <div class="left">
                @if($is_image)
                <img class="profile__img" src="{{ asset('storage/profile_images/' . Auth::id() . '.jpg') }}?v={{ time() }}" alt="プロフィール画像">
                @else
                <div class="profile__img-placeholder"></div>
                @endif
                <span class="username">
                    <h1>{{ optional(Auth::user()->profile)->user_name ?? 'ゲストユーザー' }}</h1>
                </span>
            </div>
            <div class="right">
                <a class="profile__edit-btn" href="/profile">プロフィールを編集</a>
            </div>
        </div>
    </div>

    <div class="mypage__tabs">
        <a class="tab active" data-target="listed-items">出品した商品</a>
        <a class="tab" data-target="purchased-items">購入した商品</a>
    </div>

    <div class="mypage__bottom">
        <!-- 出品した商品 -->
        <div id="listed-items" class="img__container active">
            @foreach($listedItems as $item)
            <div class="product__item">
                <a href="{{ route('detail', ['id' => $item->id]) }}" class="image__link">
                    <img src="{{ Storage::url($item->img_url) }}" alt="{{ $item->name }}" class="post__img">
                </a>
            </div>
            @endforeach
        </div>

        <!-- 購入した商品 -->
        <div id="purchased-items" class="img__container">
            @foreach($purchasedItems as $item)
            <div class="product__item">
                <a href="{{ route('detail', ['id' => $item->id]) }}" class="image__link">
                    <img src="{{ Storage::url($item->img_url) }}" alt="{{ $item->name }}" class="post__img">
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const target = tab.getAttribute('data-target');
            document.querySelectorAll('.img__container').forEach(container => {
                container.classList.remove('active');
            });
            document.getElementById(target).classList.add('active');
        });
    });
</script>
@endsection