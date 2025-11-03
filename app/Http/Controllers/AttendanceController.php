<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
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
            // Teacher sees attendance for their subjects
            $subjects = Subject::where('teacher_id', $user->id)->pluck('id');
            $query = Attendance::whereIn('subject_id', $subjects);
        } elseif ($user->role === 'etudiant') {
            // Student sees only their attendance
            $query = Attendance::where('student_id', $user->student->id);
        } elseif ($user->role === 'parent') {
            // Parent sees their child's attendance
            $query = Attendance::where('student_id', $user->parentProfile->student_id);
        } else {
            // Director sees all
            $query = Attendance::query();
        }
        
        $attendance = $query->with(['student.user', 'subject'])
            ->latest('date')
            ->paginate(20);
            
        return view('attendance.index', compact('attendance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->role === 'enseignant') {
            // Get teacher's classes and subjects
            $classes = ClassRoom::where('teacher_id', $user->id)->with('students.user')->get();
            $subjects = Subject::where('teacher_id', $user->id)->get();
            
            return view('attendance.create', compact('classes', 'subjects'));
        }
        
        abort(403, 'Unauthorized');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.reason' => 'nullable|string|max:500',
        ]);
        
        foreach ($validated['attendance'] as $record) {
            $attendance = Attendance::create([
                'student_id' => $record['student_id'],
                'subject_id' => $validated['subject_id'],
                'status' => $record['status'],
                'date' => $validated['date'],
                'reason' => $record['reason'] ?? null,
            ]);
            
            // Envoyer notification pour chaque présence enregistrée
            $this->notificationService->notifyAbsenceMarked($attendance);
        }
        
        return redirect()->route('enseignant.attendance.index')
            ->with('success', 'Présences enregistrées avec succès!');
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
    public function edit(Attendance $attendance)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'enseignant') {
            $subjects = Subject::where('teacher_id', $user->id)->pluck('id');
            if (!$subjects->contains($attendance->subject_id)) {
                abort(403, 'Unauthorized');
            }
            
            $subjects = Subject::where('teacher_id', $user->id)->get();
            
            return view('attendance.edit', compact('attendance', 'subjects'));
        }
        
        abort(403, 'Unauthorized');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'enseignant') {
            $subjects = Subject::where('teacher_id', $user->id)->pluck('id');
            if (!$subjects->contains($attendance->subject_id)) {
                abort(403, 'Unauthorized');
            }
        }
        
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'status' => 'required|in:present,absent,late,excused',
            'date' => 'required|date',
            'reason' => 'nullable|string|max:500',
        ]);
        
        // Sauvegarder l'ancien statut pour la notification
        $oldStatus = $attendance->status;
        
        $attendance->update($validated);
        
        // Envoyer notification si le statut a changé
        if ($oldStatus != $attendance->status) {
            $this->notificationService->notifyAttendanceUpdated($attendance, $oldStatus);
        }
        
        return redirect()->route('enseignant.attendance.index')
            ->with('success', 'Présence mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'enseignant') {
            $subjects = Subject::where('teacher_id', $user->id)->pluck('id');
            if (!$subjects->contains($attendance->subject_id)) {
                abort(403, 'Unauthorized');
            }
        }
        
        $attendance->delete();
        
        return redirect()->route('enseignant.attendance.index')
            ->with('success', 'Présence supprimée avec succès!');
    }
}
