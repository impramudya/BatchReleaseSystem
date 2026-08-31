<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'step',
        'order_no',
        'user_id',
        'status',
        'note',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
        'order_no' => 'integer',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stepLabel(): string
    {
        return match ($this->step) {
            'admin' => 'Admin / QA',
            'supervisor' => 'Supervisor',
            'manager' => 'Manager',
            default => ucfirst($this->step),
        };
    }
}
