<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'order_no',
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductionLine::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductionLine::class, 'parent_id')->orderBy('order_no');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(ChecklistCategory::class);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }


    public function scopeLeaves($query)
    {
        return $query->whereDoesntHave('children');
    }

    public function isLeaf(): bool
    {
        return $this->children()->doesntExist();
    }

    public function getLabelAttribute(): string
    {
        return $this->parent ? "{$this->parent->name} / {$this->name}" : $this->name;
    }
}