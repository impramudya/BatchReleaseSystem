<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_category_id',
        'parent_id',
        'order_no',
        'question',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ChecklistCategory::class, 'checklist_category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChecklistQuestion::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChecklistQuestion::class, 'parent_id')
            ->orderBy('order_no')
            ->with('children');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}