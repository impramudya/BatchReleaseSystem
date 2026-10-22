@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/product-master.css') }}?v={{ filemtime(public_path('css/product-master.css')) }}">
@endpush
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    @include('product-master._form', [
        'action' => route('product-master.update', $product->id),
        'method' => 'PUT',
        'product' => $product,
        'submitLabel' => 'Save Changes',
    ])
@endsection
