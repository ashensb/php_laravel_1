<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = ['batch_name', 'course_name', 'start_date'];

    // One batch has many students
    public function students()
    {
        return $table = $this->hasMany(Student::class);
    }
}