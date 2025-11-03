<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'grade_value',
        'max_grade',
        'exam_type',
        'date',
        'comment',
    ];

    protected $casts = [
        'date' => 'date',
        'grade_value' => 'decimal:2',
        'max_grade' => 'decimal:2',
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

    /**
     * Calculate percentage
     */
    public function getPercentageAttribute()
    {
        return ($this->grade_value / $this->max_grade) * 100;
    }
}
