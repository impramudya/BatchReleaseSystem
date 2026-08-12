@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/category-master.css') }}">

@section('title', 'Edit Kategori Produk')
@section('page-title', 'Edit Kategori Produk')

@section('content')
    @include('category-master._form', [
        'action' => route('category-master.update', $category->id),
        'method' => 'PUT',
        'category' => $category,
        'submitLabel' => 'Simpan Perubahan',
    ])
@endsection
