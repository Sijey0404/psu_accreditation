<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['subtopic_id', 'name', 'file_name', 'status'];

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }
}
