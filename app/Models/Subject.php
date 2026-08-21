<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'course_subjects';

    protected $fillable = ['course_id', 'subject_code', 'subject_name', 'description'];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher', 'subject_id', 'teacher_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'course_subject_id');
    }
}