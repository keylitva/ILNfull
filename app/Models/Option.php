<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    // Table name (if it's different from the default)
    protected $table = 'options';

    // The attributes that are mass assignable
    protected $fillable = ['question_id', 'option_text', 'option_image'];

    // Define the relationship to the Question model
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}