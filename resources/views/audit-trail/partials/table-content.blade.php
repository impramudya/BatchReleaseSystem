<div class="brp-table-wrap">
    <table class="brp-table">
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>User</th>
                <th>Action</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td class="brp-cell-strong">{{ \Illuminate\Support\Carbon::parse($log['time'])->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log['user'] }}</td>
                    <td class="audit-action-cell">{{ $log['action_label'] }}</td>
                    <td>{{ $log['detail'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="brp-empty">No audit trail records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="brp-pagination">
    {{ $logs->links() }}
</div>
