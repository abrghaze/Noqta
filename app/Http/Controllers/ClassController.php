<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'enseignant') {
            // Teacher sees only their classes
            $classes = ClassRoom::where('teacher_id', $user->id)
                ->withCount('students')
                ->with(['students.grades', 'students.attendance', 'subjects'])
                ->get();
            
            return view('teacher.classes.index', compact('classes'));
        }
        
        // Director sees all classes
        $query = ClassRoom::query();
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $classes = $query->withCount('students')->latest()->paginate(12);
        
        return view('classes.index', compact('classes'));
    }

    /**
     * Display students for teacher
     */
    public function students(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'enseignant') {
            // Get all students from teacher's classes
            $classes = ClassRoom::where('teacher_id', $user->id)->pluck('id');
            $query = \App\Models\Student::whereIn('class_id', $classes)->with(['user', 'class']);
            
            // Search functionality
            if ($request->has('search') && $request->search) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }
            
            $students = $query->paginate(15);
            
            return view('teacher.students.index', compact('students'));
        }
        
        abort(403);
    }
    
    /**
     * Show student details for teacher
     */
    public function studentShow(\App\Models\Student $student)
    {
        $user = auth()->user();
        
        if ($user->role === 'enseignant') {
            // Verify teacher has access to this student
            $classes = ClassRoom::where('teacher_id', $user->id)->pluck('id');
            
            if (!$classes->contains($student->class_id)) {
                abort(403);
            }
            
            $student->load(['user', 'class', 'grades.subject', 'attendance.subject']);
            
            return view('teacher.students.show', compact('student'));
        }
        
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role', 'enseignant')->get();
        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name',
            'teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'nullable|integer|min:1',
            'room_number' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        ClassRoom::create($validated);

        return redirect()->route('directeur.classes.index')
            ->with('success', 'Classe créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $class)
    {
        $user = auth()->user();
        
        // Load relationships
        $class->load(['students.user', 'students.grades', 'students.attendance', 'subjects']);
        
        // Check authorization for teachers
        if ($user->role === 'enseignant' && $class->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access to this class');
        }
        
        return view('teacher.classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $class)
    {
        $teachers = User::where('role', 'enseignant')->get();
        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id,
            'teacher_id' => 'nullable|exists:users,id',
            'capacity' => 'nullable|integer|min:1',
            'room_number' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $class->update($validated);

        return redirect()->route('directeur.classes.index')
            ->with('success', 'Classe mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $class)
    {
        // Check if class has students
        if ($class->students()->count() > 0) {
            return redirect()->route('directeur.classes.index')
                ->with('error', 'Impossible de supprimer une classe avec des étudiants!');
        }

        $class->delete();

        return redirect()->route('directeur.classes.index')
            ->with('success', 'Classe supprimée avec succès!');
    }
}
