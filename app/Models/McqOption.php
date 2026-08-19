<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqOption extends Model
{
    use HasFactory;

    protected $fillable = ['mcq_question_id', 'option_text', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(McqQuestion::class, 'mcq_question_id');
    }
}