<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'course_name',
        'start_date',
        'teacher_id',
    ];

    // One batch has many students
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Batch belongs to a teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subjects()
  {
    return $this->belongsToMany(Subject::class, 'batches');
  }
}