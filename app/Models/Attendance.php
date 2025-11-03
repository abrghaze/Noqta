<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'student_id',
        'subject_id',
        'status',
        'date',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Eager load relationships by default
     */
    protected $with = ['student.user', 'student.parent.user', 'subject'];

    /**
     * Relationships
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
