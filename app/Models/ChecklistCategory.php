<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_line_id',
        'code',
        'name',
    ];

    public function productionLine(): BelongsTo
    {
        return $this->belongsTo(ProductionLine::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ChecklistQuestion::class)->orderBy('order_no');
    }

    public function getLabelAttribute(): string
    {
        return "{$this->code}. {$this->name}";
    }
}
