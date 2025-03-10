<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FetchTest extends Model
{
    protected $table = 'tests';
    protected $primaryKey = 'test_id';
    public $incrementing = true;
    
    // app/Models/FetchTest.php
    protected $fillable = [
        'test_name',
        'description',
        'time_limit_minutes',
        'test_alternative_id',
        'created_by',
        'is_active'
    ];

    public function questions(): HasMany // Now using the correct HasMany class
    {
        return $this->hasMany(FetchQuestion::class, 'test_id');
    }
    public function attempts()
    {
        return $this->hasMany(TestAttempt::class, 'test_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    // In both models
}