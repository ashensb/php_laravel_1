<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'qualification',
        'img',
    ];

    /**
     * Teacher කෙනෙකුට Subjects කිහිපයක් තිබිය හැක.
     * Pivot Table: subject_teacher
     * Foreign Key: teacher_id
     * Related Key: subject_id
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'teacher_id', 'subject_id');
    }
}