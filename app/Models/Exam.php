<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_subject_id', // DB column name 
        'created_by',
        'title',
        'instructions',
        'type',
        'start_time',
        'end_time',
        'total_marks',
        'is_published',
    ];

   

   public function questions()
   {
    return $this->hasMany(McqQuestion::class, 'exam_id');
   }

    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class, 'exam_id');
    }

    public function subject()
   {
    return $this->belongsTo(Subject::class);
   }

   
}