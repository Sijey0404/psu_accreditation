<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'category',
        'status',
        'uploaded_by',
        'folder_id' // ✅ Add this to make folder association fillable
    ];

    // ✅ Optional: Define relationship to Folder model
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
