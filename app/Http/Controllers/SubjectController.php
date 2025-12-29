<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'enseignant') {
            // Teacher sees only their subjects
            $subjects = Subject::where('teacher_id', $user->id)
                ->with(['class.students'])
                ->get();
            
            return view('teacher.subjects.index', compact('subjects'));
        }
        
        // Director sees all subjects
        $query = Subject::with(['class', 'teacher']);
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        $subjects = $query->latest()->paginate(12);
        $classes = ClassRoom::all();
        
        return view('subjects.index', compact('subjects', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = ClassRoom::all();
        $teachers = User::where('role', 'enseignant')->get();
        return view('subjects.create', compact('classes', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|exists:users,id',
            'coefficient' => 'required|numeric|min:0.5|max:10',
            'description' => 'nullable|string|max:1000',
        ]);

        Subject::create($validated);

        return redirect()->route('directeur.subjects.index')
            ->with('success', 'Matière créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        $user = auth()->user();
        
        // Check authorization for teachers
        if ($user->role === 'enseignant' && $subject->teacher_id !== $user->id) {
            abort(403, 'Unauthorized access to this subject');
        }
        
        // Load relationships and grades
        $subject->load(['class.students.user', 'class.students.grades']);
        $grades = \App\Models\Grade::where('subject_id', $subject->id)
            ->with(['student.user'])
            ->latest('date')
            ->get();
        
        return view('teacher.subjects.show', compact('subject', 'grades'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $classes = ClassRoom::all();
        $teachers = User::where('role', 'enseignant')->get();
        return view('subjects.edit', compact('subject', 'classes', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|exists:users,id',
            'coefficient' => 'required|numeric|min:0.5|max:10',
            'description' => 'nullable|string|max:1000',
        ]);

        $subject->update($validated);

        return redirect()->route('directeur.subjects.index')
            ->with('success', 'Matière mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        // Check if subject has grades
        if ($subject->grades()->count() > 0) {
            return redirect()->route('directeur.subjects.index')
                ->with('error', 'Impossible de supprimer une matière avec des notes!');
        }

        $subject->delete();

        return redirect()->route('directeur.subjects.index')
            ->with('success', 'Matière supprimée avec succès!');
    }
}
