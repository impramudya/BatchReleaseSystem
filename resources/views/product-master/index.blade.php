@extends('layouts.app')

@section('title', 'Product Master')
@section('page-title', 'Product Master')

@section('content')

<link rel="stylesheet" href="{{ asset('css/product-master.css') }}">

<div id="pm-toast" class="pm-toast">
        <svg id="pm-toast-icon" class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"></svg>
        <span id="pm-toast-text"></span>
    </div>

    <div id="pm-modal-overlay" class="pm-modal-overlay">
        <div class="pm-modal" role="alertdialog" aria-modal="true" aria-labelledby="pm-modal-title">
            <div class="pm-modal-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" width="19" height="19">
                    <path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="pm-modal-title" id="pm-modal-title">Hapus produk ini?</h3>
            <p class="pm-modal-text">
                <strong id="pm-modal-name"></strong> akan dihapus permanen dari sistem.
            </p>
            <div class="pm-modal-actions">
                <button type="button" class="pm-modal-cancel" id="pm-modal-cancel">Batal</button>
                <button type="button" class="pm-modal-confirm" id="pm-modal-confirm">Hapus produk</button>
            </div>
        </div>
    </div>

    <div class="pm-card">
        <div class="pm-card-head">
            <div>
                <h3 class="pm-card-title">Product Master</h3>
                <p class="pm-card-sub">{{ $products->total() }} produk terdaftar di sistem QA</p>
            </div>
            <div class="pm-head-right">
                <div class="pm-search">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M16 16l-3.3-3.3"/></svg>
                    <input type="text" id="pm-search-input" placeholder="Cari produk / kode / kategori...">
                </div>
                <a href="{{ route('product-master.create') }}" class="pm-add-btn">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10 4.5v11M4.5 10h11"/></svg>
                    Tambah Produk
                </a>
            </div>
        </div>

        <table class="pm-table">
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="pm-table-body">
                @forelse ($products as $product)
                    <tr>
                        <td class="pm-code" title="{{ $product->product_code }}">{{ $product->product_code }}</td>
                        <td class="pm-name" title="{{ $product->name }}">{{ $product->name }}</td>
                        <td class="pm-cat" title="{{ $product->category->name ?? '' }}">{{ $product->category->name ?? '—' }}</td>
                        <td>
                            <span class="pm-badge {{ $product->status }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="pm-actions">
                                <a href="{{ route('product-master.edit', $product->id) }}" class="pm-icon-btn" title="Edit produk">
                                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.5 3.5l3 3L7 16H4v-3z"/></svg>
                                </a>
                                <form action="{{ route('product-master.destroy', $product->id) }}" method="POST" class="pm-delete-form" data-name="{{ $product->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="pm-icon-btn danger pm-delete-trigger" title="Hapus produk">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="pm-empty">Belum ada produk. Klik "Tambah Produk" untuk menambahkan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (method_exists($products, 'links'))
            <div class="pm-pagination">{{ $products->links() }}</div>
        @endif
    </div>

<script>
        window.pmFlash = {
            status: @json(session('status')),
            error: @json(session('error')),
            validationError: @json($errors->any() ? $errors->first() : null),
        };
    </script>
    <script src="{{ asset('js/product-master.js') }}"></script>

@endsection
