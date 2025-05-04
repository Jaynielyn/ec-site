@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

<x-header></x-header>

@section('main')
<div class="purchase__page">
    <div class="purchase__left">
        <form class="purchase__left-form" action="{{ route('purchase.store') }}" method="POST">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="payment_method" id="payment-method-input" value="クレジットカード">
            <div class="left__top">
                <div class="items__inner">
                    <img class="img" src="{{ $item->img_url ? Storage::url($item->img_url) : asset('img/noimage.png') }}">
                </div>
                <div class="items__inner items__name">
                    <h1 class="product__name">{{ $item->name ?? '商品名' }}</h1>
                    <p class="price">¥{{ $item->price ?? '値段' }}</p>
                </div>
            </div>

            <div class="left__items left__second">
                <div class="items__ttl">
                    <h2>支払い方法</h2>
                </div>
                <div class="items__link">
                    <a href="javascript:void(0);" id="open-payment-modal" class="blue__link">変更する</a>
                </div>
            </div>

            <div class="left__items left__third">
                <div class="items__ttl">
                    <h2>配送先</h2>
                </div>
                <div class="items__link">
                    <a href="{{ route('purchase.addressForm') }}" class="blue__link">変更する</a>
                </div>
            </div>

            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
        </form>
    </div>

    <!-- 右側 -->
    <div class="purchase__right">
        <div class="box">
            <div class="box__top">
                <div class="box__inner">
                    <label>商品代金</label>
                    <p>¥{{ $item->price ?? '値段' }}</p>
                </div>
            </div>
            <div class="box__bottom">
                <div class="box__inner">
                    <label>支払い金額</label>
                    <p>¥{{ $item->price ?? '値段' }}</p>
                </div>
                <div class="box__inner box__bottom-inner">
                    <label>支払い方法</label>
                    <p id="selected-payment-method">クレジットカード</p>
                </div>
            </div>
        </div>

        <form class="checkout__btn" action="{{ route('checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="payment_method" id="checkout-payment-method" value="クレジットカード">
            <button type="submit" class="btn">購入する</button>
        </form>
    </div>
</div>

<!-- 支払い方法モーダル -->
<div id="paymentModal" class="modal" style="display: none;">
    <div class="modal__content">
        <h2>支払い方法を選択</h2>
        <form id="payment-form">
            <label>
                <input type="radio" name="payment_method" value="クレジットカード" checked>
                クレジットカード
            </label>
            <label>
                <input type="radio" name="payment_method" value="コンビニ">
                コンビニ
            </label>
            <label>
                <input type="radio" name="payment_method" value="銀行振込">
                銀行振込
            </label>
            <button type="button" id="confirm-payment" class="btn">確定</button>
            <button type="button" class="close__modal btn">閉じる</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('paymentModal');
        const openModalButton = document.getElementById('open-payment-modal');
        const closeModalButtons = document.querySelectorAll('.close__modal');
        const confirmPaymentButton = document.getElementById('confirm-payment');
        const selectedPaymentDisplay = document.getElementById('selected-payment-method');
        const paymentMethodInput = document.getElementById('payment-method-input');
        const checkoutPaymentMethodInput = document.getElementById('checkout-payment-method');
        const paymentOptions = document.querySelectorAll('input[name="payment_method"]');

        openModalButton.addEventListener('click', function() {
            modal.style.display = 'flex';
        });

        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });

        confirmPaymentButton.addEventListener('click', function() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
            selectedPaymentDisplay.textContent = selectedPayment;
            paymentMethodInput.value = selectedPayment;
            checkoutPaymentMethodInput.value = selectedPayment;

            modal.style.display = 'none';
        });
    });
</script>
@endsection