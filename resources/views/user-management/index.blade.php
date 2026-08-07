@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('header-actions')
    <a href="{{ route('user-management.create') }}" class="um-add-btn">
        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10 4.5v11M4.5 10h11"/></svg>
        Tambah User
    </a>
@endsection

@section('content')

    <style>
        .um-card {
            background: var(--content-surface);
            border: 1px solid var(--content-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .um-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.15rem 1.4rem;
            border-bottom: 1px solid var(--content-border);
        }

        .um-card-title {
            font-family: 'poppins', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            color: var(--content-text);
        }

        .um-card-sub {
            font-family: 'poppins', sans-serif;
            font-size: 0.72rem;
            color: var(--content-text-soft);
            margin: 0.25rem 0 0;
        }

        .um-search {
            position: relative;
            width: 240px;
        }
        .um-search svg {
            position: absolute;
            left: 0.7rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--content-text-soft);
        }
        .um-search input {
            width: 100%;
            padding: 0.5rem 0.75rem 0.5rem 2.1rem;
            font-size: 0.85rem;
            font-family: 'poppins', sans-serif;
            color: var(--content-text);
            background: var(--content-bg);
            border: 1px solid var(--content-border);
            border-radius: 6px;
        }
        .um-search input:focus-visible {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(14, 124, 123, 0.15);
        }

        .um-table { width: 100%; border-collapse: collapse; font-size: 0.87rem; }

        .um-table thead th {
            text-align: left;
            padding: 0.7rem 1.4rem;
            font-family: 'poppins', monospace;
            font-size: 0.66rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--content-text-soft);
            border-bottom: 1px solid var(--content-border);
        }

        .um-table tbody tr {
            border-bottom: 1px solid var(--content-border);
            transition: background 0.12s ease;
        }
        .um-table tbody tr:last-child { border-bottom: none; }
        .um-table tbody tr:hover { background: var(--teal-tint); }
        html.dark .um-table tbody tr:hover { background: rgba(14, 124, 123, 0.08); }

        .um-table td { padding: 0.85rem 1.4rem; vertical-align: middle; color: var(--content-text); }

        .um-person { display: flex; align-items: center; gap: 0.65rem; }
        .um-avatar {
            width: 30px; height: 30px;
            border-radius: 7px;
            background: var(--teal-tint);
            color: var(--teal-dark);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.68rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        html.dark .um-avatar { background: rgba(14, 124, 123, 0.22); color: #7fd6cf; }

        .um-name { font-weight: 600; color: var(--content-text); }
        .um-email { color: var(--content-text-soft); }

        .um-role {
            display: inline-block;
            font-family: 'poppins', monospace;
            font-size: 0.68rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--teal-dark);
            border: 1px solid var(--teal);
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
        }
        html.dark .um-role { color: #7fd6cf; }

        .um-status { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; }
        .um-status .dot { width: 6px; height: 6px; border-radius: 999px; background: #1f9d6b; flex-shrink: 0; }
        .um-status.inactive .dot { background: var(--content-text-soft); }
        .um-status.inactive { color: var(--content-text-soft); }

        .um-actions { display: flex; align-items: center; gap: 0.4rem; }
        .um-icon-btn {
            width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 5px;
            border: 1px solid var(--content-border);
            background: transparent;
            color: var(--content-text-soft);
            cursor: pointer;
            text-decoration: none;
        }
        .um-icon-btn:hover { color: var(--content-text); border-color: var(--teal); }
        .um-icon-btn.danger:hover { color: var(--danger); border-color: var(--danger); }

        .um-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: var(--content-text-soft);
            font-size: 0.9rem;
        }

        .um-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: var(--teal);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 0.9rem;
            border-radius: 6px;
            text-decoration: none;
        }
        .um-add-btn:hover { background: var(--teal-dark); }

        .um-pagination { padding: 1rem 1.4rem; border-top: 1px solid var(--content-border); }

        /* Toast notification */
        .um-toast {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem 1.1rem;
            border-radius: 8px;
            font-family: 'poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            color: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            transform: translateY(-12px);
            opacity: 0;
            transition: all 0.25s ease;
            pointer-events: none;
        }
        .um-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .um-toast.success { background: #1f9d6b; }
        .um-toast.error { background: var(--danger, #dc2626); }
        .um-toast svg { flex-shrink: 0; }

        /* Modal konfirmasi delete */
        .um-modal-overlay {
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
        .um-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
.um-modal {
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
        .um-modal-overlay.show .um-modal {
            transform: translateY(0) scale(1);
        }
        .um-modal-icon {
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
        .um-modal-title {
            font-family: 'poppins', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            color: var(--content-text);
            margin: 0 0 0.35rem;
        }
        .um-modal-text {
            font-family: 'poppins', sans-serif;
            font-size: 0.8125rem;
            color: var(--content-text-soft);
            line-height: 1.55;
            margin: 0 0 1.5rem;
        }
        .um-modal-text strong {
            color: var(--content-text);
            font-weight: 600;
        }
        .um-modal-actions {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        .um-modal-actions button {
            padding: 0.5rem 1.1rem;
            border-radius: 7px;
            font-family: 'poppins', sans-serif;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background 0.12s ease, border-color 0.12s ease;
        }
        .um-modal-cancel {
            background: transparent;
            border-color: var(--content-border);
            color: var(--content-text-soft);
        }
        .um-modal-cancel:hover {
            background: var(--content-bg);
            color: var(--content-text);
        }
        .um-modal-confirm {
            background: var(--danger, #dc2626);
            color: #fff;
        }
        .um-modal-confirm:hover {
            background: color-mix(in srgb, var(--danger, #dc2626) 85%, black);
        }
        .um-modal-confirm:active {
            transform: scale(0.98);
        }
    </style>

    <div id="um-toast" class="um-toast">
        <svg id="um-toast-icon" class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" width="18" height="18"></svg>
        <span id="um-toast-text"></span>
    </div>

    <div id="um-modal-overlay" class="um-modal-overlay">
        <div class="um-modal" role="alertdialog" aria-modal="true" aria-labelledby="um-modal-title">
            <div class="um-modal-icon">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" width="19" height="19">
                    <path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="um-modal-title" id="um-modal-title">Hapus pengguna ini?</h3>
            <p class="um-modal-text">
                <strong id="um-modal-name"></strong> akan kehilangan akses sepenuhnya. Data yang sudah dihapus tidak bisa dikembalikan.
            </p>
            <div class="um-modal-actions">
                <button type="button" class="um-modal-cancel" id="um-modal-cancel">Batal</button>
                <button type="button" class="um-modal-confirm" id="um-modal-confirm">Hapus pengguna</button>
            </div>
        </div>
    </div>

    <div class="um-card">
        <div class="um-card-head">
            <div>
                <h3 class="um-card-title">Daftar Pengguna</h3>
                <p class="um-card-sub">{{ $users->total() }} akun terdaftar di sistem QA</p>
            </div>
            <div class="um-search">
                <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M16 16l-3.3-3.3"/></svg>
                <input type="text" id="um-search-input" placeholder="Cari nama atau email...">
            </div>
        </div>

        <table class="um-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="um-table-body">
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="um-person">
                                <div class="um-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                <span class="um-name">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="um-email">{{ $user->email }}</td>
                        <td><span class="um-role">{{ $user->role }}</span></td>
                        <td>
                            @if(($user->status ?? 'active') === 'active')
                                <span class="um-status"><span class="dot"></span> Aktif</span>
                            @else
                                <span class="um-status inactive"><span class="dot"></span> Nonaktif</span>
                            @endif
                        </td>
                        <td class="um-email">{{ $user->last_login ? $user->last_login->format('d M Y') : '—' }}</td>
                        <td>
                            <div class="um-actions">
                                <a href="{{ route('user-management.edit', $user->id) }}" class="um-icon-btn" title="Edit pengguna">
                                    <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M13.5 3.5l3 3L7 16H4v-3z"/></svg>
                                </a>
                                <form action="{{ route('user-management.reset-password', $user->id) }}" method="POST" onsubmit="return confirm('Reset password {{ $user->name }} dengan password sementara?');">
                                    @csrf
                                    <button type="submit" class="um-icon-btn" title="Reset password">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7" cy="13" r="3"/><path d="M9.2 10.8L15.5 4.5M13.5 6.5l2 2M15.5 4.5l2 2"/></svg>
                                    </button>
                                </form>
                                <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="um-delete-form" data-name="{{ $user->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="um-icon-btn danger um-delete-trigger" title="Hapus pengguna">
                                        <svg class="brp-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.5 6h11M8 6V4.5h4V6M6 6l.6 9.5h6.8L14 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="um-empty">Belum ada pengguna. Klik "Tambah User" untuk menambahkan akun baru.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (method_exists($users, 'links'))
            <div class="um-pagination">{{ $users->links() }}</div>
        @endif
    </div>

    <script>
        (function () {
            // Search
            const input = document.getElementById('um-search-input');
            const rows = document.querySelectorAll('#um-table-body tr');
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
                const toast = document.getElementById('um-toast');
                const text = document.getElementById('um-toast-text');
                const icon = document.getElementById('um-toast-icon');

                text.textContent = message;
                toast.className = 'um-toast ' + type;

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
            const overlay = document.getElementById('um-modal-overlay');
            const modalName = document.getElementById('um-modal-name');
            const btnCancel = document.getElementById('um-modal-cancel');
            const btnConfirm = document.getElementById('um-modal-confirm');
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

            document.querySelectorAll('.um-delete-trigger').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openModal(btn.closest('.um-delete-form'));
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
