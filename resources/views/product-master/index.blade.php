@extends('layouts.app')

@section('title', 'Product Master')
@section('page-title', 'Product Master')

@section('content')

    <style>
        .pm-card {
            background: var(--content-surface);
            border: 1px solid var(--content-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .pm-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--content-border);
        }

        .pm-card-title {
            font-family: 'poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.3;
            margin: 0;
            color: var(--content-text);
        }

        .pm-card-sub {
            font-family: 'poppins', sans-serif;
            font-size: 0.75rem;
            line-height: 1.4;
            color: var(--content-text-soft);
            margin: 0.25rem 0 0;
        }

        .pm-head-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pm-search {
            position: relative;
            width: 260px;
        }
        .pm-search svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--content-text-soft);
            pointer-events: none;
        }
        .pm-search input {
            width: 100%;
            padding: 0.55rem 0.75rem 0.55rem 2.25rem;
            font-size: 0.85rem;
            font-family: 'poppins', sans-serif;
            line-height: 1.4;
            color: var(--content-text);
            background: var(--content-bg);
            border: 1px solid var(--content-border);
            border-radius: 6px;
        }
        .pm-search input:focus-visible {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(14, 124, 123, 0.15);
        }

        .pm-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            table-layout: fixed;
        }

        .pm-table thead th {
            text-align: left;
            padding: 0.75rem 1.5rem;
            font-family: 'poppins', monospace;
            font-size: 0.6875rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--content-text-soft);
            border-bottom: 1px solid var(--content-border);
            white-space: nowrap;
        }

        .pm-table thead th:nth-child(1) { width: 15%; }
        .pm-table thead th:nth-child(2) { width: 32%; }
        .pm-table thead th:nth-child(3) { width: 22%; }
        .pm-table thead th:nth-child(4) { width: 13%; }
        .pm-table thead th:nth-child(5) { width: 18%; text-align: right; }

        .pm-table tbody tr {
            border-bottom: 1px solid var(--content-border);
            transition: background 0.12s ease;
        }
        .pm-table tbody tr:last-child { border-bottom: none; }
        .pm-table tbody tr:hover { background: var(--teal-tint); }
        html.dark .pm-table tbody tr:hover { background: rgba(14, 124, 123, 0.08); }

        .pm-table td {
            padding: 0.875rem 1.5rem;
            vertical-align: middle;
            color: var(--content-text);
            line-height: 1.45;
        }
        .pm-table td:last-child { text-align: right; }

        .pm-code {
            font-family: 'poppins', monospace;
            font-size: 0.8rem;
            color: var(--content-text-soft);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pm-name {
            font-weight: 600;
            color: var(--content-text);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .pm-cat {
            color: var(--content-text-soft);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pm-badge {
            display: inline-block;
            font-family: 'poppins', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            line-height: 1.4;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            white-space: nowrap;
        }
        .pm-badge.active {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
        }
        .pm-badge.inactive {
            background: color-mix(in srgb, var(--content-text-soft) 18%, transparent);
            color: var(--content-text-soft);
        }

        .pm-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .pm-icon-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            border: 1px solid var(--content-border);
            background: transparent;
            color: var(--content-text-soft);
            cursor: pointer;
            text-decoration: none;
            transition: color 0.12s ease, border-color 0.12s ease;
        }
        .pm-icon-btn:hover { color: var(--content-text); border-color: var(--teal); }
        .pm-icon-btn.danger:hover { color: var(--danger); border-color: var(--danger); }

        .pm-empty {
            padding: 3.5rem 1.5rem;
            text-align: center;
            color: var(--content-text-soft);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .pm-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--teal);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.4;
            padding: 0.55rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.12s ease;
        }
        .pm-add-btn:hover { background: var(--teal-dark); }

        .pm-pagination {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--content-border);
        }

        .pm-toast {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.875rem 1.25rem;
            border-radius: 8px;
            font-family: 'poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            line-height: 1.4;
            color: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            transform: translateY(-12px);
            opacity: 0;
            transition: transform 0.25s ease, opacity 0.25s ease;
            pointer-events: none;
        }
        .pm-toast.show { transform: translateY(0); opacity: 1; }
        .pm-toast.success { background: #1f9d6b; }
        .pm-toast.error { background: var(--danger, #dc2626); }

        .pm-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 21, 0.5);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.18s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.18s;
        }
        .pm-modal-overlay.show { opacity: 1; visibility: visible; }
        .pm-modal {
            background: var(--content-surface);
            border: 1px solid var(--content-border);
            border-radius: 12px;
            padding: 1.75rem 1.75rem 1.5rem;
            width: 100%;
            max-width: 336px;
            text-align: center;
            box-shadow:
                0 1px 2px rgba(0, 0, 0, 0.04),
                0 12px 28px -6px rgba(0, 0, 0, 0.22),
                0 24px 48px -12px rgba(0, 0, 0, 0.18);
            transform: translateY(6px) scale(0.98);
            transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .pm-modal-overlay.show .pm-modal { transform: translateY(0) scale(1); }
        .pm-modal-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: color-mix(in srgb, var(--danger, #dc2626) 10%, transparent);
            color: var(--danger, #dc2626);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        .pm-modal-title {
            font-family: 'poppins', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            line-height: 1.35;
            color: var(--content-text);
            margin: 0 0 0.5rem;
        }
        .pm-modal-text {
            font-family: 'poppins', sans-serif;
            font-size: 0.8125rem;
            color: var(--content-text-soft);
            line-height: 1.55;
            margin: 0 0 1.5rem;
        }
        .pm-modal-text strong { color: var(--content-text); font-weight: 600; }
        .pm-modal-actions {
            display: flex;
            justify-content: center;
            gap: 0.625rem;
        }
        .pm-modal-actions button {
            padding: 0.55rem 1.15rem;
            border-radius: 7px;
            font-family: 'poppins', sans-serif;
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.3;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background 0.12s ease, border-color 0.12s ease;
        }
        .pm-modal-cancel {
            background: transparent;
            border-color: var(--content-border);
            color: var(--content-text-soft);
        }
        .pm-modal-cancel:hover { background: var(--content-bg); color: var(--content-text); }
        .pm-modal-confirm { background: var(--danger, #dc2626); color: #fff; }
        .pm-modal-confirm:hover { background: color-mix(in srgb, var(--danger, #dc2626) 85%, black); }
        .pm-modal-confirm:active { transform: scale(0.98); }

        @media (max-width: 640px) {
            .pm-card-head { flex-direction: column; align-items: stretch; }
            .pm-head-right { flex-direction: column; align-items: stretch; }
            .pm-search { width: 100%; }
        }
    </style>

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
        (function () {
            const input = document.getElementById('pm-search-input');
            const rows = document.querySelectorAll('#pm-table-body tr');
            if (input) {
                input.addEventListener('input', function () {
                    const q = input.value.trim().toLowerCase();
                    rows.forEach(function (row) {
                        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
                    });
                });
            }

            function showToast(message, type) {
                const toast = document.getElementById('pm-toast');
                const text = document.getElementById('pm-toast-text');
                const icon = document.getElementById('pm-toast-icon');
                text.textContent = message;
                toast.className = 'pm-toast ' + type;
                icon.innerHTML = type === 'success' ? '<path d="M4 10.5l4 4 8-9"/>' : '<path d="M6 6l8 8M14 6l-8 8"/>';
                setTimeout(function () { toast.classList.add('show'); }, 50);
                setTimeout(function () { toast.classList.remove('show'); }, 3500);
            }

            @if (session('status'))
                showToast(@json(session('status')), 'success');
            @endif
            @if (session('error'))
                showToast(@json(session('error')), 'error');
            @endif
            @if ($errors->any())
                showToast(@json($errors->first()), 'error');
            @endif

            const overlay = document.getElementById('pm-modal-overlay');
            const modalName = document.getElementById('pm-modal-name');
            const btnCancel = document.getElementById('pm-modal-cancel');
            const btnConfirm = document.getElementById('pm-modal-confirm');
            let formToSubmit = null;

            function openModal(form) {
                formToSubmit = form;
                modalName.textContent = form.dataset.name;
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                overlay.classList.remove('show');
                document.body.style.overflow = '';
                formToSubmit = null;
            }

            document.querySelectorAll('.pm-delete-trigger').forEach(function (btn) {
                btn.addEventListener('click', function () { openModal(btn.closest('.pm-delete-form')); });
            });
            btnCancel.addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal(); });
            btnConfirm.addEventListener('click', function () { if (formToSubmit) formToSubmit.submit(); });
        })();
    </script>

@endsection
