<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FetchQuestionsOption extends Model
{
    protected $table = 'options'; // Specify your table
    protected $primaryKey = 'option_id';
    public $timestamps = false; // Set to true if you're using timestamps

    protected $fillable = [
        'question_id', 'option_text', 'option_image', 'is_correct', 'input_type', 'gap_id'
    ];

    // Relationship with question
    public function question()
    {
        return $this->belongsTo(FetchQuestion::class, 'question_id');
    }
}