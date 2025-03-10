<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FetchQuestion extends Model
{
    protected $table = 'questions'; // Specify your table
    protected $primaryKey = 'question_id';
    public $timestamps = false; // Set to true if you're using timestamps
    protected $appends = ['parsed_text'];
    protected $fillable = [
        'test_id',
        'question_text',
        'question_type',
        'additional_data'
    ];
    public function test()
    {
        return $this->belongsTo(FetchTest::class, 'test_id'); // Ensure the foreign key is correct
    }
    public function getParsedTextAttribute()
    {
        // Assuming you need to parse `question_text`
        return strip_tags($this->attributes['question_text']); // Example: stripping HTML tags
    }
}
