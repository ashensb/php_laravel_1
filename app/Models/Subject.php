<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'course_subjects';

    protected $fillable = ['course_id', 'subject_code', 'subject_name', 'description'];

    // Subject එකට Teachers ලා කිහිපදෙනෙක් සිටිය හැක (Many-to-Many)
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher', 'subject_id', 'teacher_id');
    }

    // Subject එකකට Exams කිහිපයක් තිබිය හැක
    public function exams()
    {
        return $this->hasMany(Exam::class, 'subject_id');
    }
}