@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-master.css') }}?v={{ filemtime(public_path('css/product-master.css')) }}">
@endpush

@section('title', 'Add Product')
@section('page-title', 'Add Product')

@section('content')
    @include('product-master._form', [
        'action' => route('product-master.store'),
        'method' => 'POST',
        'product' => null,
        'submitLabel' => 'Save Product',
    ])
@endsection
