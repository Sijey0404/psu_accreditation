<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'area',
        'department_id',
        'strengths',
        'improvements',
        'recommendations',
        'rating',
        'evaluator_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
} 