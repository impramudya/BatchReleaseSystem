<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_category_id',
        'order_no',
        'question',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ChecklistCategory::class, 'checklist_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
