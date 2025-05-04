@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/mail.css') }}">
@endsection

<x-admin_header></x-admin_header>

@section('main')
<div class="mail__page">
    <h1>メール送信 - {{ $user->profile->user_name ?? 'No Name' }}</h1>

    <form action="{{ route('admin.users.sendMail', $user->id) }}" method="POST">
        @csrf
        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" required>

        <label for="body">Message:</label>
        <textarea name="body" id="body" rows="5" required></textarea>

        <button type="submit">送信</button>
    </form>
</div>
@endsection