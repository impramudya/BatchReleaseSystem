@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/category-master.css') }}?v=4">

@section('title', 'Edit Product Category')
@section('page-title', 'Edit Product Category')

@section('content')
    @include('category-master._form', [
        'action' => route('category-master.update', $category->id),
        'method' => 'PUT',
        'category' => $category,
        'submitLabel' => 'Save Changes',
    ])
@endsection
