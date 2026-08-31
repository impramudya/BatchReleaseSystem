<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuditTrailExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $logs)
    {
    }

    public function collection(): Collection
    {
        return $this->logs;
    }

    public function headings(): array
    {
        return ['Waktu', 'User', 'Aksi', 'Detail'];
    }

    /**
     * @param array $log
     */
    public function map($log): array
    {
        return [
            \Illuminate\Support\Carbon::parse($log['time'])->format('d/m/Y H:i:s'),
            $log['user'],
            $log['action_label'],
            $log['detail'],
        ];
    }
}
