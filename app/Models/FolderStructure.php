<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderStructure extends Model
{
    protected $fillable = [
        'area_key',
        'area_name',
        'folders',
    ];

    protected $casts = [
        'folders' => 'array',
    ];
}
