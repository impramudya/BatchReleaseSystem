{{-- product-master/edit.blade.php --}}
@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/product-master.css') }}">
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
    @include('product-master._form', [
        'action' => route('product-master.update', $product->id),
        'method' => 'PUT',
        'product' => $product,
        'categories' => $categories,
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection
