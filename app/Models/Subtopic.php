<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'name'];

    public function department()
    {
        // Each subtopic belongs to a department
        return $this->belongsTo(Department::class);
    }
    
    
    public function folders()
{
    return $this->hasMany(Folder::class);
}

}
