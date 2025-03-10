<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Table name (if it's different from the default)
    protected $table = 'questions';
    protected $primaryKey = 'question_id';
    // The attributes that are mass assignable
    // app/Models/Question.php
    protected $fillable = [
        'test_id',
        'question_text',
        'type',
        'options',
        'question_type',
        'correct_answer',
        'max_score'
    ];
    public function test(): BelongsTo
    {
        return $this->belongsTo(FetchTest::class, 'test_id');
    }
    // Define the relationship to the Option model
    public function options()
    {
        return $this->hasMany(Option::class, 'question_id');
    }
    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
    ];
}