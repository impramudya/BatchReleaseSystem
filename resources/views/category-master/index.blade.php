@extends('layouts.app')

@section('title', 'Category Master')
@section('page-title', 'Category Master')

@section('content')
<link rel="stylesheet" href="{{ asset('css/category-master.css') }}?v=4">

<div id="cm-toast" class="cm-toast">
        <svg id="cm-toast-icon" class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"></svg>
        <span id="cm-toast-text"></span>
    </div>

    <div id="cm-modal-overlay" class="cm-modal-overlay">
        <div class="cm-modal" role="alertdialog" aria-modal="true" aria-labelledby="cm-modal-title">
            <div class="pm-modal-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" width="19" height="19">
                    <path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="cm-modal-title" id="cm-modal-title">Delete this category?</h3>
            <p class="cm-modal-text">
                <strong id="cm-modal-name"></strong> will be permanently deleted. Categories currently assigned to products cannot be deleted.
            </p>
            <div class="cm-modal-actions">
                <button type="button" class="cm-modal-cancel" id="cm-modal-cancel">Cancel</button>
                <button type="button" class="cm-modal-confirm" id="cm-modal-confirm">Delete Category</button>
            </div>
        </div>
    </div>

    <div class="cm-card">
        <div class="cm-card-head">
            <div>
                <h3 class="cm-card-title">Category Master</h3>
                <p class="cm-card-sub">{{ $categories->total() }} categories registered in QA system</p>
            </div>
            <div class="cm-head-right">
                <div class="cm-search">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M16 16l-3.3-3.3"/></svg>
                    <input type="text" id="cm-search-input" placeholder="Search category...">
                </div>
                <a href="{{ route('category-master.create') }}" class="cm-add-btn">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10 4.5v11M4.5 10h11"/></svg>
                    Add Category
                </a>
            </div>
        </div>

        <table class="cm-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cm-table-body">
                @forelse ($categories as $category)
                    <tr>
                        <td class="cm-name" title="{{ $category->name }}">{{ $category->name }}</td>
                        <td class="cm-desc" title="{{ $category->description }}">{{ $category->description ?: '—' }}</td>
                        <td>
                            <div class="cm-actions">
                                <a href="{{ route('category-master.edit', $category->id) }}" class="cm-icon-btn" title="Edit category">
                                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.5 3.5l3 3L7 16H4v-3z"/></svg>
                                </a>
                                <form action="{{ route('category-master.destroy', $category->id) }}" method="POST" class="cm-delete-form" data-name="{{ $category->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="cm-icon-btn danger cm-delete-trigger" title="Delete category">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="cm-empty">No categories found. Click "Add Category" to create one.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (method_exists($categories, 'links'))
            <div class="cm-pagination">{{ $categories->links() }}</div>
        @endif
    </div>

<script>
        window.cmFlash = {
            status: @json(session('status')),
            error: @json(session('error')),
            validationError: @json($errors->any() ? $errors->first() : null),
        };
    </script>
    <script src="{{ asset('js/category-master.js') }}?v=4"></script>

@endsection
