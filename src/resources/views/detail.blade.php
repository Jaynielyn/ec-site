@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection
<x-header></x-header>

@section('main')
<div class="detail__page">
    <form method="get">
        @csrf
        <div class="detail__form form__left">
            <div class="detail__image">
                <img class="detail__img" src="{{ $item->img_url ? Storage::disk('s3')->url($item->img_url) : asset('img/noimage.png') }}">
            </div>
        </div>

        <div class="detail__form form__right">
            <div class="detail__items">
                <div class="detail__ttl">
                    <h1 class="product__name">{{ $item->name ?? '商品名' }}</h1>
                    <p class="brand__name">{{ $item->brand ?? 'ブランド名' }}</p>
                    <p class="price">¥{{ $item->price ?? '値段' }}</p>
                </div>

                <div class="detail__act">
                    <div class="act__like">
                        <img id="likeButton"
                            class="stars"
                            src="{{ auth()->check() && auth()->user()->likes->contains('item_id', $item->id) ? asset('img/star-solid.svg') : asset('img/star-regular.svg') }}"
                            alt="いいね"
                            data-item-id="{{ $item->id }}">
                        <span id="likeCount" class="act__count">{{ $item->likes->count() ?? 0 }}</span>
                    </div>

                    <script>
                        document.getElementById('likeButton').addEventListener('click', function() {
                            const itemId = this.getAttribute('data-item-id');

                            fetch(`/like/${itemId}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const likeCountElement = document.getElementById('likeCount');
                                    const likeButton = document.getElementById('likeButton');
                                    if (data.status === 'liked') {
                                        likeButton.src = '{{ asset("img/star-solid.svg") }}';
                                    } else {
                                        likeButton.src = '{{ asset("img/star-regular.svg") }}';
                                    }

                                    likeCountElement.textContent = data.likeCount;
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    </script>

                    <a href="{{ route('comment.show', ['item_id' => $item->id]) }}" class="act__comment">
                        <img class="comments" src="{{ asset('img/comment-regular.svg') }}" alt="コメント">
                        <span id="commentCount" class="act__count">{{ $item->comments ? $item->comments->count() : 0 }}</span>
                    </a>
                </div>
            </div>

            <div class="detail__btn">
                <a class="btn" href="{{ route('purchase', ['id' => $item->id]) }}">購入する</a>
            </div>

            <div class="detail__items detail__info">
                <h2 class="section__title">商品説明</h2>
                <p class="description__content">{{ $item->description ?? '商品の状態は良好です。傷もありません。' }}</p>
            </div>

            <div class="detail__items detail__info">
                <h2 class="section__title">商品の情報</h2>
                <div class="detail__sub">
                    <label class="label">カテゴリー：</label>
                    <div class="content__txt">
                        <p class="category__txt">{{ $item->category ?? 'カテゴリー' }}</p>
                    </div>
                </div>
                <div class="detail__sub detail__condition">
                    <label class="label">商品の状態：</label>
                    <div class="content__txt">
                        <p class="condition__txt">{{ $item->condition ?? '状態' }}</p>
                    </div>
                </div>
            </div>
    </form>
</div>
@endsection