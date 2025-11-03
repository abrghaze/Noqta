<?php

namespace App\Http\Controllers;

use App\Models\{User, Student, Teacher, ClassRoom, Grade, Attendance, Subject};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role
     */
    public function index()
    {
        $user = auth()->user();

        return match($user->role) {
            'directeur' => $this->directeurDashboard(),
            'enseignant' => $this->enseignantDashboard(),
            'etudiant' => $this->etudiantDashboard(),
            'parent' => $this->parentDashboard(),
            default => abort(403)
        };
    }

    /**
     * Director Dashboard - Overall statistics
     */
    private function directeurDashboard()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = ClassRoom::count();
        $totalSubjects = Subject::count();

        // Attendance statistics
        $today = now()->toDateString();
        $todayAttendance = Attendance::whereDate('date', $today)->get();
        $presentToday = $todayAttendance->where('status', 'present')->count();
        $absentToday = $todayAttendance->where('status', 'absent')->count();

        // Recent grades
        $recentGrades = Grade::with(['student.user', 'subject'])
            ->latest()
            ->take(10)
            ->get();

        // Attendance rate by class
        $attendanceByClass = ClassRoom::withCount([
            'students',
            'students as present_count' => function($query) use ($today) {
                $query->whereHas('attendance', function($q) use ($today) {
                    $q->whereDate('date', $today)->where('status', 'present');
                });
            }
        ])->get();

        return view('dashboard.directeur-modern', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'totalSubjects',
            'presentToday',
            'absentToday',
            'recentGrades',
            'attendanceByClass'
        ));
    }

    /**
     * Teacher Dashboard - Classes and students overview
     */
    private function enseignantDashboard()
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        // Get teacher's classes
        $classes = ClassRoom::where('teacher_id', $user->id)
            ->withCount('students')
            ->get();

        // Get teacher's subjects
        $subjects = Subject::where('teacher_id', $user->id)
            ->with('class')
            ->get();

        // Recent attendance records for teacher's subjects
        $recentAttendance = Attendance::whereIn('subject_id', $subjects->pluck('id'))
            ->with(['student.user', 'subject'])
            ->latest()
            ->take(10)
            ->get();

        // Students in teacher's classes
        $students = Student::whereIn('class_id', $classes->pluck('id'))
            ->with('user')
            ->get();

        return view('dashboard.enseignant-modern', compact(
            'classes',
            'subjects',
            'recentAttendance',
            'students'
        ));
    }

    /**
     * Student Dashboard - Personal grades and attendance
     */
    private function etudiantDashboard()
    {
        $user = auth()->user();
        $student = $user->student;

        // Check if student profile exists
        if (!$student) {
            return view('dashboard.etudiant-modern', [
                'error' => 'Profil étudiant non trouvé. Contactez l\'administrateur.',
            ]);
        }

        // Get student's grades
        $grades = Grade::where('student_id', $student->id)
            ->with('subject')
            ->latest()
            ->get();

        // Calculate average
        $average = $grades->avg('grade_value');

        // Get attendance records
        $attendance = Attendance::where('student_id', $student->id)
            ->with('subject')
            ->latest()
            ->take(20)
            ->get();

        // Attendance statistics
        $totalAttendance = Attendance::where('student_id', $student->id)->count();
        $presentCount = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->count();
        $attendanceRate = $totalAttendance > 0 ? ($presentCount / $totalAttendance) * 100 : 0;

        // Get student's class and subjects
        $class = $student->class;
        $subjects = $class ? $class->subjects : collect();

        return view('dashboard.etudiant-modern', compact(
            'student',
            'grades',
            'average',
            'attendance',
            'attendanceRate',
            'class',
            'subjects'
        ));
    }

    /**
     * Parent Dashboard - Child's information
     */
    private function parentDashboard()
    {
        $user = auth()->user();
        $parentProfile = $user->parentProfile;

        // Check if parent profile exists
        if (!$parentProfile) {
            return view('dashboard.parent-modern', [
                'error' => 'Profil parent non trouvé. Contactez l\'administrateur.',
                'children' => collect(),
            ]);
        }

        // Get ALL children linked to this parent
        $children = Student::where('parent_id', $parentProfile->id)
            ->with(['user', 'class'])
            ->get();

        // If no children, show message
        if ($children->isEmpty()) {
            return view('dashboard.parent-modern', [
                'message' => 'Aucun enfant lié à votre compte.',
                'children' => collect(),
            ]);
        }

        // Get first child (or selected child from session)
        $student = $children->first();

        // Get student's grades
        $grades = Grade::where('student_id', $student->id)
            ->with('subject')
            ->latest()
            ->get();

        // Calculate average
        $average = $grades->avg('grade_value');

        // Get attendance records
        $attendance = Attendance::where('student_id', $student->id)
            ->with('subject')
            ->latest()
            ->take(20)
            ->get();

        // Attendance statistics
        $totalAttendance = Attendance::where('student_id', $student->id)->count();
        $presentCount = Attendance::where('student_id', $student->id)
            ->where('status', 'present')
            ->count();
        $attendanceRate = $totalAttendance > 0 ? ($presentCount / $totalAttendance) * 100 : 0;

        // Get student's class
        $class = $student->class;

        return view('dashboard.parent-modern', compact(
            'student',
            'grades',
            'average',
            'attendance',
            'attendanceRate',
            'class',
            'children'
        ));
    }
}
