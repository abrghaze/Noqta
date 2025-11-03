<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\ParentProfile;
use App\Models\Grade;
use App\Models\Attendance;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $query = Student::with(['user', 'classRoom', 'parent.user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('matricule', 'like', '%' . $request->search . '%');
        }

        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by parent
        if ($request->has('parent_id') && $request->parent_id) {
            $query->where('parent_id', $request->parent_id);
        }

        $students = $query->latest()->paginate(15);
        $classes = ClassRoom::all();
        $parents = ParentProfile::with('user')->get();

        return view('director.students.index', compact('students', 'classes', 'parents'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $classes = ClassRoom::all();
        $parents = ParentProfile::with('user')->get();

        return view('director.students.create', compact('classes', 'parents'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
            'matricule' => 'required|string|unique:students,matricule',
            'date_of_birth' => 'required|date',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $student = Student::create($validated);

        return redirect()->route('directeur.students.index')
            ->with('success', 'Étudiant créé avec succès!');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load(['user', 'classRoom', 'parent.user', 'grades.subject', 'attendance.subject']);

        $stats = [
            'average_grade' => $student->averageGrade(),
            'attendance_rate' => $student->attendanceRate(),
            'total_grades' => $student->grades()->count(),
            'total_absences' => $student->attendance()->where('status', 'absent')->count(),
        ];

        return view('director.students.show', compact('student', 'stats'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        $classes = ClassRoom::all();
        $parents = ParentProfile::with('user')->get();

        return view('director.students.edit', compact('student', 'classes', 'parents'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'parent_id' => 'nullable|exists:parents,id',
            'matricule' => 'required|string|unique:students,matricule,' . $student->id,
            'date_of_birth' => 'required|date',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $student->update($validated);

        return redirect()->route('directeur.students.index')
            ->with('success', 'Étudiant mis à jour avec succès!');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        // Delete related records (grades, attendance) will cascade
        $student->delete();

        return redirect()->route('directeur.students.index')
            ->with('success', 'Étudiant supprimé avec succès!');
    }
}
