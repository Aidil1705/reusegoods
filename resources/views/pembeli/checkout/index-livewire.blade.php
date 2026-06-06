@extends('layouts.pembeli')

@section('title', 'Checkout - ReGoods')

@section('content')
    @livewire('checkout', [
        'items' => $items,
        'subtotal' => $subtotal,
        'shippingCost' => $shippingCost,
        'isBuyNow' => $isBuyNow,
    ])
@endsection
