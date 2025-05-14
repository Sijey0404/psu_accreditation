<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtopic_id',
        'title',
        'file_path',
        'category',
        'original_link',
        'status',
        'rejection_reason',
        'approval_feedback',
        'uploaded_by',
        'folder_id',
        'file_type',
        'approved_at',
        'approved_by',
        'drive_id',
    ];

    /**
     * Relationships
     */

    // A document belongs to a folder
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    // A document belongs to a subtopic
    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }

    // A document was uploaded by a user
    public function uploader()
    {
    return $this->belongsTo(User::class, 'uploaded_by');
    }
    // A document can be approved by a user (QA/Accreditor)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    protected $table = 'documents'; // ✅ Your actual table name

    public function user()
{
    return $this->belongsTo(User::class);
}



}
