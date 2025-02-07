<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Table name (if it's different from the default)
    protected $table = 'questions';

    // The attributes that are mass assignable
    protected $fillable = ['test_id', 'question_text', 'question_type'];

    // Define the relationship to the Option model
    public function options()
    {
        return $this->hasMany(Option::class, 'question_id');
    }
}