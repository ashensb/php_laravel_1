<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_no', 
        'name', 
        'email', 
        'dob', 
        'age', 
        'password', 
        'img', 
        'batch_id'
    ];

    // Student belongs to a single batch
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}