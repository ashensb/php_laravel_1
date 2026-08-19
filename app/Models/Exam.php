<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'reg_no', 'qualification', 'phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Teacher කෙනෙකුට Subjects කිහිපයක් උගන්වන්න පුළුවන්
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id');
    }
}