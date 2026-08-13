<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Semua pertanyaan milik kategori ini, terurut sesuai order_no.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(ChecklistQuestion::class)->orderBy('order_no');
    }

    /**
     * Label gabungan untuk ditampilkan, contoh: "A. Prosedur Pengolahan Induk".
     */
    public function getLabelAttribute(): string
    {
        return "{$this->code}. {$this->name}";
    }
}
