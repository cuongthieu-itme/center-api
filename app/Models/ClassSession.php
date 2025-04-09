<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    use HasFactory;

    protected $table = 'class_sessions';

    protected $fillable = [
        'class_id',
        'session_date',
        'start_time',
        'end_time'
    ];

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
