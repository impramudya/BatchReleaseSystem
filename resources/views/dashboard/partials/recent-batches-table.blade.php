<div class="brp-table-wrap">
    <table class="brp-table">
        <thead>
            <tr>
                <th>Batch No</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Manufacturer</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentBatches as $batch)
            <tr>
                <td class="brp-cell-strong">{{ $batch->batch_number }}</td>
                <td>{{ $batch->product->product_code ?? '-' }}</td>
                <td>{{ $batch->product->name ?? '-' }}</td>
                <td>{{ $batch->manufacturer }}</td>
                <td>
                    <span class="{{ $batch->statusBadgeClass() }} brp-status-badge brp-status-{{ $batch->status }}">
                        {{ $batch->statusLabel() }}
                    </span>
                </td>
                <td>{{ $batch->created_at?->format('d M Y') ?? '—' }}</td>
                <td>
                    @if ($batch->status === 'draft')
                    <a href="{{ route('batch-form.edit', $batch) }}" class="brp-btn-outline">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" style="width:14px;height:14px;">
                            <path d="M13.3 3.3a1.5 1.5 0 0 1 2.1 2.1L6 15.1l-2.8.7.7-2.8L13.3 3.3Z" /></svg>
                        Edit
                    </a>
                    @else
                    <a href="{{ route('batch-repository.show', $batch) }}" class="brp-btn-outline">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" style="width:14px;height:14px;">
                            <path d="M1.5 10S4.5 4 10 4s8.5 6 8.5 6-3 6-8.5 6-8.5-6z" />
                            <circle cx="10" cy="10" r="2.25" /></svg>
                        View
                    </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="brp-empty">No batches found matching the filter criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="brp-pagination">
    {{ $recentBatches->links() }}
</div>
