@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
<x-header></x-header>

@section('main')
<div class="index__page">
    <div class="top__page">
        <a id="recommendationTab" class="recommendation active" href="#">おすすめ</a>
        <a id="myListTab" class="top__mypage" href="#">マイリスト</a>
    </div>

    <div id="recommendationContent" class="tab__content" style="display: block;">
        <div class="recommendation">
            <div class="img__container">
                @foreach ($images as $image)
                <a href="/detail/{{ $image->id }}" class="image__link">
                    <img class="post__img" src="{{ Storage::disk('s3')->url($image->img_url) }}" alt="{{ $image->name }}">
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <div id="myListContent" class="tab__content" style="display: none;">
        <div class="recommendation">
            <div class="img__container">
                @foreach ($likedItems as $item)
                <a href="/detail/{{ $item->id }}" class="image__link">
                    <img class="post__img" src="{{ Storage::disk('s3')->url($item->img_url) }}" alt="{{ $item->name }}">
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('recommendationTab').addEventListener('click', function() {
        document.getElementById('recommendationContent').style.display = 'block';
        document.getElementById('myListContent').style.display = 'none';
        this.classList.add('active');
        document.getElementById('myListTab').classList.remove('active');
    });

    document.getElementById('myListTab').addEventListener('click', function() {
        document.getElementById('recommendationContent').style.display = 'none';
        document.getElementById('myListContent').style.display = 'block';
        this.classList.add('active');
        document.getElementById('recommendationTab').classList.remove('active');
    });
</script>
@endsection