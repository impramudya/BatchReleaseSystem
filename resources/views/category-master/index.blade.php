@extends('layouts.app')

@section('title', 'Master Kategori Produk')
@section('page-title', 'Master Kategori Produk')

@section('content')

    <style>
        .cm-card {
            background: var(--content-surface);
            border: 1px solid var(--content-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .cm-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--content-border);
        }

        .cm-card-title {
            font-family: 'poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.3;
            margin: 0;
            color: var(--content-text);
        }

        .cm-card-sub {
            font-family: 'poppins', sans-serif;
            font-size: 0.75rem;
            line-height: 1.4;
            color: var(--content-text-soft);
            margin: 0.25rem 0 0;
        }

        .cm-head-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .cm-search {
            position: relative;
            width: 240px;
        }
        .cm-search svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--content-text-soft);
            pointer-events: none;
        }
        .cm-search input {
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
        .cm-search input:focus-visible {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(14, 124, 123, 0.15);
        }

        .cm-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            table-layout: fixed;
        }

        .cm-table thead th {
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

        .cm-table thead th:first-child { width: 26%; }
        .cm-table thead th:nth-child(2) { width: 54%; }
        .cm-table thead th:last-child { width: 20%; text-align: right; }

        .cm-table tbody tr {
            border-bottom: 1px solid var(--content-border);
            transition: background 0.12s ease;
        }
        .cm-table tbody tr:last-child { border-bottom: none; }
        .cm-table tbody tr:hover { background: var(--teal-tint); }
        html.dark .cm-table tbody tr:hover { background: rgba(14, 124, 123, 0.08); }

        .cm-table td {
            padding: 0.875rem 1.5rem;
            vertical-align: middle;
            color: var(--content-text);
            line-height: 1.45;
        }
        .cm-table td:last-child { text-align: right; }

        .cm-name {
            font-weight: 600;
            color: var(--content-text);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .cm-desc {
            color: var(--content-text-soft);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .cm-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .cm-icon-btn {
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
        .cm-icon-btn:hover { color: var(--content-text); border-color: var(--teal); }
        .cm-icon-btn.danger:hover { color: var(--danger); border-color: var(--danger); }

        .cm-empty {
            padding: 3.5rem 1.5rem;
            text-align: center;
            color: var(--content-text-soft);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .cm-add-btn {
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
        .cm-add-btn:hover { background: var(--teal-dark); }

        .cm-pagination {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--content-border);
        }

        /* Toast notification */
        .cm-toast {
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
        .cm-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .cm-toast.success { background: #1f9d6b; }
        .cm-toast.error { background: var(--danger, #dc2626); }
        .cm-toast svg { flex-shrink: 0; }

        /* Modal konfirmasi delete */
        .cm-modal-overlay {
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
        .cm-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .cm-modal {
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
        .cm-modal-overlay.show .cm-modal {
            transform: translateY(0) scale(1);
        }
        .cm-modal-icon {
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
        .cm-modal-title {
            font-family: 'poppins', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            line-height: 1.35;
            color: var(--content-text);
            margin: 0 0 0.5rem;
        }
        .cm-modal-text {
            font-family: 'poppins', sans-serif;
            font-size: 0.8125rem;
            color: var(--content-text-soft);
            line-height: 1.55;
            margin: 0 0 1.5rem;
        }
        .cm-modal-text strong {
            color: var(--content-text);
            font-weight: 600;
        }
        .cm-modal-actions {
            display: flex;
            justify-content: center;
            gap: 0.625rem;
        }
        .cm-modal-actions button {
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
        .cm-modal-cancel {
            background: transparent;
            border-color: var(--content-border);
            color: var(--content-text-soft);
        }
        .cm-modal-cancel:hover {
            background: var(--content-bg);
            color: var(--content-text);
        }
        .cm-modal-confirm {
            background: var(--danger, #dc2626);
            color: #fff;
        }
        .cm-modal-confirm:hover {
            background: color-mix(in srgb, var(--danger, #dc2626) 85%, black);
        }
        .cm-modal-confirm:active {
            transform: scale(0.98);
        }

        @media (max-width: 640px) {
            .cm-card-head {
                flex-direction: column;
                align-items: stretch;
            }
            .cm-head-right {
                flex-direction: column;
                align-items: stretch;
            }
            .cm-search { width: 100%; }
        }
    </style>

    <div id="cm-toast" class="cm-toast">
        <svg id="cm-toast-icon" class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"></svg>
        <span id="cm-toast-text"></span>
    </div>

    <div id="cm-modal-overlay" class="cm-modal-overlay">
        <div class="cm-modal" role="alertdialog" aria-modal="true" aria-labelledby="cm-modal-title">
            <div class="cm-modal-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" width="19" height="19">
                    <path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="cm-modal-title" id="cm-modal-title">Hapus kategori ini?</h3>
            <p class="cm-modal-text">
                <strong id="cm-modal-name"></strong> akan dihapus permanen. Kategori yang masih dipakai produk tidak bisa dihapus.
            </p>
            <div class="cm-modal-actions">
                <button type="button" class="cm-modal-cancel" id="cm-modal-cancel">Batal</button>
                <button type="button" class="cm-modal-confirm" id="cm-modal-confirm">Hapus kategori</button>
            </div>
        </div>
    </div>

    <div class="cm-card">
        <div class="cm-card-head">
            <div>
                <h3 class="cm-card-title">Master Kategori Produk</h3>
                <p class="cm-card-sub">{{ $categories->total() }} kategori terdaftar di sistem QA</p>
            </div>
            <div class="cm-head-right">
                <div class="cm-search">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M16 16l-3.3-3.3"/></svg>
                    <input type="text" id="cm-search-input" placeholder="Cari kategori...">
                </div>
                <a href="{{ route('category-master.create') }}" class="cm-add-btn">
                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10 4.5v11M4.5 10h11"/></svg>
                    Tambah Kategori
                </a>
            </div>
        </div>

        <table class="cm-table">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="cm-table-body">
                @forelse ($categories as $category)
                    <tr>
                        <td class="cm-name" title="{{ $category->name }}">{{ $category->name }}</td>
                        <td class="cm-desc" title="{{ $category->description }}">{{ $category->description ?? '—' }}</td>
                        <td>
                            <div class="cm-actions">
                                <a href="{{ route('category-master.edit', $category->id) }}" class="cm-icon-btn" title="Edit kategori">
                                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.5 3.5l3 3L7 16H4v-3z"/></svg>
                                </a>
                                <form action="{{ route('category-master.destroy', $category->id) }}" method="POST" class="cm-delete-form" data-name="{{ $category->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="cm-icon-btn danger cm-delete-trigger" title="Hapus kategori">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="cm-empty">Belum ada kategori. Klik "Tambah Kategori" untuk menambahkan.</div>
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
        (function () {
            // Search
            const input = document.getElementById('cm-search-input');
            const rows = document.querySelectorAll('#cm-table-body tr');
            if (input) {
                input.addEventListener('input', function () {
                    const q = input.value.trim().toLowerCase();
                    rows.forEach(function (row) {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(q) ? '' : 'none';
                    });
                });
            }

            // Toast
            function showToast(message, type) {
                const toast = document.getElementById('cm-toast');
                const text = document.getElementById('cm-toast-text');
                const icon = document.getElementById('cm-toast-icon');

                text.textContent = message;
                toast.className = 'cm-toast ' + type;

                icon.innerHTML = type === 'success'
                    ? '<path d="M4 10.5l4 4 8-9"/>'
                    : '<path d="M6 6l8 8M14 6l-8 8"/>';

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

            // Modal konfirmasi delete
            const overlay = document.getElementById('cm-modal-overlay');
            const modalName = document.getElementById('cm-modal-name');
            const btnCancel = document.getElementById('cm-modal-cancel');
            const btnConfirm = document.getElementById('cm-modal-confirm');
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

            document.querySelectorAll('.cm-delete-trigger').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openModal(btn.closest('.cm-delete-form'));
                });
            });

            btnCancel.addEventListener('click', closeModal);

            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeModal();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && overlay.classList.contains('show')) closeModal();
            });

            btnConfirm.addEventListener('click', function () {
                if (formToSubmit) formToSubmit.submit();
            });
        })();
    </script>

@endsection
