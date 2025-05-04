@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/success.css') }}">
@endsection

<x-header></x-header>

@section('main')
@push('head')
<meta http-equiv="refresh" content="3;url={{ route('index') }}">
@endpush

<div class="success__container">
    <h1>Success!</h1>
</div>
@endsection