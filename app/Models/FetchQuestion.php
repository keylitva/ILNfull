<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FetchQuestion extends Model
{
    protected $table = 'questions'; // Specify your table
    protected $primaryKey = 'question_id';
    public $timestamps = false; // Set to true if you're using timestamps
    
    protected $fillable = [
        'test_id', 'question_text', 'question_type', 'additional_data'
    ];

    // Define the relationship to fetch options
    public function options()
    {
        return $this->hasMany(FetchQuestionsOption::class, 'question_id');
    }
}