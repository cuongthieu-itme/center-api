<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

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
        return $this->belongsToMany(ClassModel::class, 'student_classes', 'student_id', 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
