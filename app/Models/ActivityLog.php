<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'action_label',
        'target_type',
        'target_id',
        'details',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
