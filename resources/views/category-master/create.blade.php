@extends('layouts.app')

@section('title', 'Tambah Kategori Produk')
@section('page-title', 'Tambah Kategori Produk')

@section('content')
    @include('category-master._form', [
        'action' => route('category-master.store'),
        'method' => 'POST',
        'category' => null,
        'submitLabel' => 'Simpan Kategori',
    ])
@endsection
