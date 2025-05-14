<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'name', 'has_generated_folders'];

    public function department()
    {
        // Each subtopic belongs to a department
        return $this->belongsTo(Department::class);
    }
    
    
    public function folders()
{
    return $this->hasMany(Folder::class);
}

public function documents()
{
    return $this->hasMany(Document::class);
}







}
