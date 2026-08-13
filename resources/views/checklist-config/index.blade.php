@extends('layouts.app')

@section('title', 'Checklist Config')
@section('page-title', 'Konfigurasi Checklist')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/checklist-config.css') }}">
@endpush

@section('content')

    @if (session('status'))
        <div class="ccfg-flash">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="ccfg-flash ccfg-flash-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="ccfg-layout">
        @include('checklist-config.partials.category-panel')
        @include('checklist-config.partials.question-panel')
    </div>

    @include('checklist-config.partials.modal-add-category')
    @include('checklist-config.partials.modal-edit-category')

    @if ($selectedCategory)
        @include('checklist-config.partials.modal-add-question')
    @endif

    @include('checklist-config.partials.modal-confirm-delete')
    @include('checklist-config.partials.modal-edit-question')

@endsection

@push('scripts')
    <script src="{{ asset('js/checklist-config.js') }}" defer></script>
@endpush
