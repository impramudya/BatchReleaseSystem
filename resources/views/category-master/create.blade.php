@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/category-master.css') }}?v=4">

@section('title', 'Add Product Category')
@section('page-title', 'Add Product Category')

@section('content')
    @include('category-master._form', [
        'action' => route('category-master.store'),
        'method' => 'POST',
        'category' => null,
        'submitLabel' => 'Save Category',
    ])
@endsection
