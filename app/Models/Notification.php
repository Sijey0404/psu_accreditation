<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'link',
        'data',
        'is_read',
        'notified_roles'
    ];

    protected $casts = [
        'data' => 'array',
        'notified_roles' => 'array',
        'is_read' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 