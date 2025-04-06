<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaDocument extends Model
{
    // Allow mass assignment of the following fields
    protected $fillable = ['folder_id', 'title', 'file_path'];

    // Relationship: each document belongs to a folder
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
