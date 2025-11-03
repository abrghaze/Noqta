<?php

namespace App\Http\Controllers;

use App\Models\ParentProfile;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Attendance;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Display a listing of parents
     */
    public function index(Request $request)
    {
        $query = ParentProfile::with(['user', 'children.user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        $parents = $query->latest()->paginate(15);

        return view('director.parents.index', compact('parents'));
    }

    /**
     * Show the form for creating a new parent
     */
    public function create()
    {
        return view('director.parents.create');
    }

    /**
     * Store a newly created parent
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'relationship' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $parent = ParentProfile::create($validated);

        return redirect()->route('directeur.parents.index')
            ->with('success', 'Parent créé avec succès!');
    }

    /**
     * Display the specified parent
     */
    public function show(ParentProfile $parent)
    {
        $parent->load(['user', 'children.user', 'children.classRoom']);

        return view('director.parents.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified parent
     */
    public function edit(ParentProfile $parent)
    {
        return view('director.parents.edit', compact('parent'));
    }

    /**
     * Update the specified parent
     */
    public function update(Request $request, ParentProfile $parent)
    {
        $validated = $request->validate([
            'relationship' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $parent->update($validated);

        return redirect()->route('directeur.parents.index')
            ->with('success', 'Parent mis à jour avec succès!');
    }

    /**
     * Remove the specified parent
     */
    public function destroy(ParentProfile $parent)
    {
        // Unlink children (set parent_id to null)
        $parent->children()->update(['parent_id' => null]);

        $parent->delete();

        return redirect()->route('directeur.parents.index')
            ->with('success', 'Parent supprimé avec succès!');
    }

    /**
     * View child's grades (for parent viewing their child)
     */
    public function childGrades(Student $child)
    {
        $user = auth()->user();
        $parent = $user->parentProfile;

        // Verify this child belongs to this parent
        if (!$parent || $child->parent_id !== $parent->id) {
            abort(403, 'Non autorisé');
        }

        $grades = Grade::where('student_id', $child->id)
            ->with(['subject', 'teacher.user'])
            ->latest()
            ->get();

        $average = $child->averageGrade();

        return view('parent.grades', compact('child', 'grades', 'average'));
    }

    /**
     * View child's attendance (for parent viewing their child)
     */
    public function childAttendance(Student $child)
    {
        $user = auth()->user();
        $parent = $user->parentProfile;

        // Verify this child belongs to this parent
        if (!$parent || $child->parent_id !== $parent->id) {
            abort(403, 'Non autorisé');
        }

        $attendance = Attendance::where('student_id', $child->id)
            ->with('subject')
            ->latest()
            ->get();

        $attendanceRate = $child->attendanceRate();

        return view('parent.attendance', compact('child', 'attendance', 'attendanceRate'));
    }

    /**
     * View teachers (for parent)
     */
    public function teachers()
    {
        $user = auth()->user();
        $parent = $user->parentProfile;

        if (!$parent) {
            abort(403, 'Profil parent non trouvé');
        }

        $children = $parent->children;
        
        // Get all teachers who teach any of the parent's children
        $teacherIds = [];
        foreach ($children as $child) {
            if ($child->classRoom) {
                $teacherIds[] = $child->classRoom->teacher_id;
            }
        }

        $teachers = Teacher::whereIn('user_id', array_unique($teacherIds))
            ->with(['user', 'subjects'])
            ->get();

        return view('parent.teachers', compact('teachers', 'children'));
    }
}
