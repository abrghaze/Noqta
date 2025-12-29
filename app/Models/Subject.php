<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'class_id',
        'teacher_id',
        'coefficient',
        'description',
    ];

    /**
     * Relationships
     */
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // Alias for class() to avoid PHP reserved word issues
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
