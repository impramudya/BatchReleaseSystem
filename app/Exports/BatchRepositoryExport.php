<?php

namespace App\Exports;

use App\Models\Batch;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BatchRepositoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private array $filters = [], private bool $hideDraft = false)
    {
    }

    public function collection(): Collection
    {
        return Batch::query()
            ->filter($this->filters)
            ->when($this->hideDraft, fn ($q) => $q->where('status', '!=', 'draft'))
            ->with(['product', 'productionLine.parent', 'supervisor', 'manager', 'creator'])
            ->orderByDesc('batch_date')
            ->orderByDesc('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Batch No',
            'Line',
            'Product Code',
            'Product Name',
            'Manufacturer',
            'Admin',
            'Supervisor',
            'Manager',
            'Status',
            'Batch Date',
            'Released Date',
        ];
    }

    public function map($batch): array
    {
        return [
            $batch->batch_number,
            $batch->lineLabel(),
            $batch->product->product_code ?? '-',
            $batch->product->name ?? '-',
            $batch->manufacturer,
            $batch->creator->name ?? '-',
            $batch->supervisor->name ?? '-',
            $batch->manager->name ?? '-',
            $batch->statusLabel(),
            $batch->batch_date?->format('Y-m-d'),
            $batch->released_at?->format('Y-m-d H:i') ?? '-',
        ];
    }
}
