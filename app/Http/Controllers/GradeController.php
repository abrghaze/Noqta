<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'enseignant') {
            // Teacher sees grades for their subjects
            $subjects = Subject::where('teacher_id', $user->id)->pluck('id');
            $query = Grade::whereIn('subject_id', $subjects);
        } elseif ($user->role === 'etudiant') {
            // Student sees only their grades
            $query = Grade::where('student_id', $user->student->id);
        } elseif ($user->role === 'parent') {
            // Parent sees their child's grades
            $query = Grade::where('student_id', $user->parentProfile->student_id);
        } else {
            // Director sees all
            $query = Grade::query();
        }
        
        $grades = $query->with(['student.user', 'subject'])
            ->latest()
            ->paginate(20);
            
        return view('grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->role === 'enseignant') {
            // Get teacher's subjects and their students
            $subjects = Subject::where('teacher_id', $user->id)->with('class.students.user')->get();
            $students = collect();
            
            foreach ($subjects as $subject) {
                if ($subject->class) {
                    $students = $students->merge($subject->class->students);
                }
            }
            
            $students = $students->unique('id');
            
            return view('grades.create', compact('subjects', 'students'));
        }
        
        abort(403, 'Unauthorized');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade_value' => 'required|numeric|min:0',
            'max_grade' => 'required|numeric|min:0',
            'exam_type' => 'required|in:Composition,Devoir,Contrôle',
            'date' => 'required|date',
            'comment' => 'nullable|string|max:500',
        ]);
        
        $grade = Grade::create($validated);
        
        // Envoyer les notifications (étudiant + parents)
        $this->notificationService->notifyGradeAdded($grade);
        
        return redirect()->route('enseignant.grades.index')
            ->with('success', 'Note ajoutée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'enseignant') {
            $subjects = Subject::where('teacher_id', $user->id)->pluck('id');
            if (!$subjects->contains($grade->subject_id)) {
                abort(403, 'Unauthorized');
            }
            
            // Get teacher's subjects and students
            $subjects = Subject::where('teacher_id', $user->id)->with('class.students.user')->get();
            $students = collect();
            
            foreach ($subjects as $subject) {
                if ($subject->class) {
                    $students = $students->merge($subject->class->students);
                }
            }
            
            $students = $students->unique('id');
            
            return view('grades.edit', compact('grade', 'subjects', 'students'));
        }
        
        abort(403, 'Unauthorized');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'enseignant') {
            $subjects = Subject::where('teacher_id', $user->id)->pluck('id');
            if (!$subjects->contains($grade->subject_id)) {
                abort(403, 'Unauthorized');
            }
        }
        
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade_value' => 'required|numeric|min:0',
            'max_grade' => 'required|numeric|min:0',
            'exam_type' => 'required|in:Composition,Devoir,Contrôle',
            'date' => 'required|date',
            'comment' => 'nullable|string|max:500',
        ]);
        
        // Sauvegarder l'ancienne valeur pour la notification
        $oldValue = $grade->grade_value;
        
        $grade->update($validated);
        
        // Envoyer les notifications si la note a changé
        if ($oldValue != $grade->grade_value) {
            $this->notificationService->notifyGradeUpdated($grade, $oldValue);
        }
        
        return redirect()->route('enseignant.grades.index')
            ->with('success', 'Note mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'enseignant') {
            $subjects = Subject::where('teacher_id', $user->id)->pluck('id');
            if (!$subjects->contains($grade->subject_id)) {
                abort(403, 'Unauthorized');
            }
        }
        
        // Envoyer les notifications avant de supprimer
        $this->notificationService->notifyGradeDeleted($grade);
        
        $grade->delete();
        
        return redirect()->route('enseignant.grades.index')
            ->with('success', 'Note supprimée avec succès!');
    }
}
