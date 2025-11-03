<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers
     */
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'subjects', 'classes']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('specialization', 'like', '%' . $request->search . '%');
        }

        // Filter by specialization
        if ($request->has('specialization') && $request->specialization) {
            $query->where('specialization', 'like', '%' . $request->specialization . '%');
        }

        $teachers = $query->latest()->paginate(15);

        return view('director.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher
     */
    public function create()
    {
        return view('director.teachers.create');
    }

    /**
     * Store a newly created teacher
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialization' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $teacher = Teacher::create($validated);

        return redirect()->route('directeur.teachers.index')
            ->with('success', 'Enseignant créé avec succès!');
    }

    /**
     * Display the specified teacher
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects.classRoom', 'classes.students']);

        $stats = [
            'total_classes' => $teacher->classes()->count(),
            'total_subjects' => $teacher->subjects()->count(),
            'total_students' => $teacher->classes()->withCount('students')->get()->sum('students_count'),
        ];

        return view('director.teachers.show', compact('teacher', 'stats'));
    }

    /**
     * Show the form for editing the specified teacher
     */
    public function edit(Teacher $teacher)
    {
        return view('director.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'specialization' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $teacher->update($validated);

        return redirect()->route('directeur.teachers.index')
            ->with('success', 'Enseignant mis à jour avec succès!');
    }

    /**
     * Remove the specified teacher
     */
    public function destroy(Teacher $teacher)
    {
        // Check if teacher has subjects assigned
        if ($teacher->subjects()->count() > 0) {
            return redirect()->route('directeur.teachers.index')
                ->with('error', 'Impossible de supprimer cet enseignant car il a des matières assignées. Veuillez d\'abord réassigner les matières.');
        }

        $teacher->delete();

        return redirect()->route('directeur.teachers.index')
            ->with('success', 'Enseignant supprimé avec succès!');
    }
}
