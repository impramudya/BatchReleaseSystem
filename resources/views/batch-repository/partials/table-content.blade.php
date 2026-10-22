<div class="brp-table-wrap">
    <table class="brp-table">
        <thead>
            <tr>
                <th>Batch No</th>
                <th>Manufacturer</th>
                <th>Admin</th>
                <th>Supervisor</th>
                <th>Manager</th>
                <th>Status</th>
                <th>Released Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($batches as $batch)
            <tr>
                <td class="brp-cell-strong">{{ $batch->batch_number }}</td>
                <td>{{ $batch->manufacturer }}</td>
                <td>{{ $batch->creator_display_name }}</td>
                <td>{{ $batch->supervisor_display_name }}</td>
                <td>{{ $batch->manager_display_name }}</td>
                <td><span class="{{ $batch->statusBadgeClass() }}">{{ $batch->statusLabel() }}</span></td>
                <td>{{ $batch->released_at?->format('d M Y') ?? '—' }}</td>
                <td>
                    @if ($batch->status === 'draft')
                    <a href="{{ route('batch-form.edit', $batch) }}" class="brp-icon-btn" title="Edit Draft">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M13.3 3.3a1.5 1.5 0 0 1 2.1 2.1L6 15.1l-2.8.7.7-2.8L13.3 3.3Z" /></svg>
                    </a>
                    @else
                    <a href="{{ route('batch-repository.show', $batch) }}" class="brp-icon-btn" title="View Details">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M1.5 10S4.5 4 10 4s8.5 6 8.5 6-3 6-8.5 6-8.5-6Z" />
                            <circle cx="10" cy="10" r="2.25" /></svg>
                    </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="brp-empty">No batches recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="brp-pagination">
    {{ $batches->links() }}
</div>
