<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['subtopic_id', 'name', 'path'];

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }
    
    
    public function areaDocuments()
{
    return $this->hasMany(AreaDocument::class);
}

public function documents()
{
    return $this->hasMany(AreaDocument::class);
}


}

