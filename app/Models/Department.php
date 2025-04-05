<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($department) {
            $department->slug = Str::slug($department->name);
        });
    }

    public function subtopics()
    {
        return $this->hasMany(Subtopic::class);
    }
}
