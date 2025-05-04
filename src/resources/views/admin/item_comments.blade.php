@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/item_comments.css') }}">
@endsection
<x-admin_header></x-admin_header>

@section('main')
<div class="container">
    <h1>{{ $user->profile->user_name ?? 'No Name' }}さんがコメントした商品</h1>

    <div class="recommendation">
        <div class="img__container">
            @forelse ($images as $image)
            <a href="{{ route('admin.items.details', ['item' => $image->id]) }}" class="image__link">
                <img class="post__img" src="{{ $image->img_url }}" alt="{{ $image->name }}">
            </a>
            @empty
            <p>コメントした商品はありません。</p>
            @endforelse
        </div>
    </div>
</div>
@endsection