<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'question', 'marks'];

    // Relation with MCQ Options
    public function options()
    {
        return $this->hasMany(McqOption::class, 'mcq_question_id');
    }
}