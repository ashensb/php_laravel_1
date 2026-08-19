<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'reg_no', 'qualification', 'phone', 'name', 'email'];

   public function user()
   {
    return $this->belongsTo(User::class, 'user_id');
   }

    // Teacher කෙනෙකුට Subjects කිහිපයක් උගන්වන්න පුළුවන්
    public function subjects()
   
   {
    // belongsToMany(RelatedModel, pivot_table, foreignPivotKey, relatedPivotKey)
    return $this->belongsToMany(Subject::class, 'subject_teacher', 'user_id', 'subject_id');
   }
}