<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'parent_id',
        'matricule',
        'date_of_birth',
        'phone',
        'address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // Alias for class() to avoid PHP reserved word issues
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }

    /**
     * Calculate average grade
     */
    public function averageGrade()
    {
        $grades = $this->grades()->with('subject')->get();
        
        if ($grades->isEmpty()) {
            return 0;
        }

        $totalWeighted = 0;
        $totalCoefficient = 0;

        foreach ($grades as $grade) {
            $coefficient = $grade->subject->coefficient ?? 1;
            $totalWeighted += $grade->grade_value * $coefficient;
            $totalCoefficient += $coefficient;
        }

        return $totalCoefficient > 0 ? round($totalWeighted / $totalCoefficient, 2) : 0;
    }

    /**
     * Calculate attendance rate
     */
    public function attendanceRate()
    {
        $totalAttendance = $this->attendance()->count();
        
        if ($totalAttendance === 0) {
            return 0;
        }

        $presentCount = $this->attendance()->where('status', 'present')->count();
        
        return round(($presentCount / $totalAttendance) * 100, 2);
    }
}
