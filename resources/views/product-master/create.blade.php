{{-- product-master/create.blade.php --}}
@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/product-master.css') }}">

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')
    @include('product-master._form', [
        'action' => route('product-master.store'),
        'method' => 'POST',
        'product' => null,
        'categories' => $categories,
        'submitLabel' => 'Simpan Produk',
    ])
@endsection
