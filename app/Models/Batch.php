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
        'batch_validasi',
        'laporan_validasi',
        'nomor_laporan_validasi',
        'laporan_penyimpangan',
        'penyimpangan_items',
        'change_control',
        'change_control_items',
        'checklist_snapshot',
        'status',
        'released_at',
        'created_by',
        'creator_name',
        'supervisor_name',
        'manager_name',
    ];

    protected $casts = [
        'batch_date' => 'date',
        'released_at' => 'datetime',
        'checklist_snapshot' => 'array',
        'penyimpangan_items' => 'array',
        'change_control_items' => 'array',
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

    public function getCreatorDisplayNameAttribute(): string
    {
        return $this->creator_name ?? $this->creator?->name ?? '-';
    }

    public function getSupervisorDisplayNameAttribute(): string
    {
        return $this->supervisor_name ?? $this->supervisor?->name ?? '-';
    }

    public function getManagerDisplayNameAttribute(): string
    {
        return $this->manager_name ?? $this->manager?->name ?? '-';
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

    /**
     * Membekukan (snapshot) susunan checklist pada saat submit agar permanen & tidak terpengaruh
     * oleh penambahan/perubahan/penghapusan master Checklist Config di masa depan.
     */
    public function generateChecklistSnapshot(?array $answers = null, ?array $notes = null): array
    {
        $lineId = $this->production_line_id;
        if (! $lineId) {
            return [];
        }

        if ($answers === null) {
            $answers = $this->answers()->pluck('answer', 'checklist_question_id')->toArray();
        }
        if ($notes === null) {
            $notes = $this->answers()->pluck('keterangan', 'checklist_question_id')->toArray();
        }

        $categories = ChecklistCategory::where('production_line_id', $lineId)
            ->orderBy('code')
            ->with(['questions' => function ($q) {
                $q->whereNull('parent_id')
                    ->where('status', 'active')
                    ->orderBy('order_no')
                    ->with(['children' => function ($c) {
                        $c->where('status', 'active')
                            ->orderBy('order_no')
                            ->with(['children' => function ($g) {
                                $g->where('status', 'active')->orderBy('order_no');
                            }]);
                    }]);
            }])
            ->get();

        $snapshot = [];

        foreach ($categories as $cat) {
            $categoryRows = [];
            $parentNo = 0;

            foreach ($cat->questions as $parent) {
                $parentNo++;
                $hasChildren = $parent->children->isNotEmpty();
                $ans = $answers[$parent->id] ?? null;
                $note = $notes[$parent->id] ?? null;

                $categoryRows[] = [
                    'id' => $parent->id,
                    'question' => $parent->question,
                    'details' => $parent->details ?? null,
                    'depth' => 0,
                    'parent_no' => $parentNo,
                    'is_checkable' => ! $hasChildren,
                    'answer' => ! $hasChildren ? $ans : null,
                    'keterangan' => ! $hasChildren ? $note : null,
                    'category_code' => $cat->code,
                    'category_name' => $cat->name,
                ];

                foreach ($parent->children as $child) {
                    $childAns = $answers[$child->id] ?? null;
                    $childNote = $notes[$child->id] ?? null;

                    $categoryRows[] = [
                        'id' => $child->id,
                        'question' => $child->question,
                        'details' => $child->details ?? null,
                        'depth' => 1,
                        'parent_no' => null,
                        'is_checkable' => true,
                        'answer' => $childAns,
                        'keterangan' => $childNote,
                        'category_code' => $cat->code,
                        'category_name' => $cat->name,
                    ];

                    foreach ($child->children as $grandson) {
                        $categoryRows[] = [
                            'id' => $grandson->id,
                            'question' => $grandson->question,
                            'depth' => 2,
                            'parent_no' => null,
                            'is_checkable' => false,
                            'answer' => null,
                            'keterangan' => null,
                            'category_code' => $cat->code,
                            'category_name' => $cat->name,
                        ];
                    }
                }
            }

            if (! empty($categoryRows)) {
                $snapshot[$cat->code] = $categoryRows;
            }
        }

        return $snapshot;
    }
}
