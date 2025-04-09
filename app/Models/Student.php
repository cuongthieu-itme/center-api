<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'full_name',
        'dob',
        'phone',
        'email',
        'address',
        'avatar_url',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'student_classes');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
