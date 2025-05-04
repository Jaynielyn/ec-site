@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('main')
<x-logo></x-logo>
<div class="login__page">
    <form class="form" action="{{ route('admin.login.submit') }}" method="post">
        @csrf
        <div class="login__form">
            <h1 class="login__ttl">管理画面<br>ログイン</h1>
        </div>

        <!-- 全体のエラーメッセージ -->
        @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="login__form form__item">
            <label class="login__form-label" for="name">名前</label>
            <input class="login__input input__name" id="name" type="text" name="name" required autofocus>

            @if ($errors->has('name'))
            <div class="error">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div class="login__form form__item">
            <label class="login__form-label">メールアドレス</label>
            <input class="login__input input__email" type="email" name="email" value="{{ old('email') }}" />

            @if ($errors->has('email'))
            <div class="error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="login__form form__item">
            <label class="login__form-label">パスワード</label>
            <input class="login__input input__pass" type="password" name="password" />

            @if ($errors->has('password'))
            <div class="error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="login__form form__btn">
            <input class="login__input input__btn" type="submit" value="ログインする">
        </div>

        <div class="login__form login__link">
            <a class="link" href="{{ route('admin.register') }}">会員登録はこちら</a>
        </div>
    </form>
</div>
@endsection