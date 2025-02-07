<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAttempt extends Model
{
    protected $table = 'test_attempts';
    protected $primaryKey = 'attempt_id';
    public $incrementing = true;
    protected $fillable = ['score', 'answers', 'points_collected', 'points_max', 'time_taken', 'updated_at', 'attempt_date', 'test_id', 'user_id'];
    protected $casts = [
        'attempt_date' => 'datetime',
    ];
}