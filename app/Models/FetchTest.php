<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FetchTest extends Model
{
    protected $table = 'tests';
    protected $primaryKey = 'test_id';
    public $incrementing = true;

    public function attempts()
    {
        return $this->hasMany(TestAttempt::class, 'test_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}