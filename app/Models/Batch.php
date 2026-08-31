<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_number',
        'manufacturer',
        'production_type',
        'production_line_id',
        'batch_date',
        'supervisor_id',
        'manager_id',
        'keterangan',
        'status',
        'released_at',
        'created_by',
    ];

    protected $casts = [
        'batch_date' => 'date',
        'released_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productionLine(): BelongsTo
    {
        return $this->belongsTo(ProductionLine::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(BatchEvaluationAnswer::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(BatchApproval::class)->orderBy('order_no');
    }

    public function approvalFor(string $step): ?BatchApproval
    {
        return $this->approvals->firstWhere('step', $step);
    }

    /**
     * Scope filter bersama untuk index, export Excel, dan export PDF Batch Repository.
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['batch_number'] ?? null, function ($q, $v) {
                $q->where('batch_number', 'like', '%'.$v.'%');
            })
            ->when($filters['product_code'] ?? null, function ($q, $v) {
                $q->whereHas('product', fn ($qq) => $qq->where('product_code', 'like', '%'.$v.'%'));
            })
            ->when($filters['status'] ?? null, function ($q, $v) {
                $q->where('status', $v);
            })
            ->when($filters['date_from'] ?? null, function ($q, $v) {
                $q->whereDate('batch_date', '>=', $v);
            })
            ->when($filters['date_to'] ?? null, function ($q, $v) {
                $q->whereDate('batch_date', '<=', $v);
            })
            ->when($filters['line_code'] ?? null, function ($q, $v) {
                $q->whereHas('productionLine', fn ($qq) => $qq->where('code', $v));
            });
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Waiting Supervisor',
            'waiting_manager' => 'Waiting Manager',
            'released' => 'Released',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'draft' => 'brp-badge brp-badge-draft',
            'submitted', 'waiting_manager' => 'brp-badge brp-badge-waiting',
            'released' => 'brp-badge brp-badge-released',
            'rejected' => 'brp-badge brp-badge-rejected',
            default => 'brp-badge',
        };
    }

    public function lineLabel(): string
    {
        if (! $this->productionLine) {
            return '-';
        }

        return $this->productionLine->parent
            ? $this->productionLine->parent->name.' / '.$this->productionLine->name
            : $this->productionLine->name;
    }

    public function lineBadgeClass(): string
    {
        return match ($this->productionLine->code ?? null) {
            'inhouse_stripping' => 'brp-line brp-line-stripping',
            'inhouse_bottling' => 'brp-line brp-line-bottling',
            'toll_out' => 'brp-line brp-line-tollout',
            default => 'brp-line',
        };
    }
}
