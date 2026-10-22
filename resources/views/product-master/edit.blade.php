@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/product-master.css') }}?v=4">
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
