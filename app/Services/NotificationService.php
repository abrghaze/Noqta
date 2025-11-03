<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;

class NotificationService
{
    /**
     * Créer une notification pour un utilisateur
     */
    public function create(User $user, string $type, string $title, string $message, array $data = [])
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Notification quand une note est ajoutée
     */
    public function notifyGradeAdded(Grade $grade)
    {
        $student = $grade->student;
        $subject = $grade->subject;
        
        // Notification pour l'étudiant
        $this->create(
            $student->user,
            'grade_added',
            'Nouvelle note ajoutée',
            "Vous avez reçu une nouvelle note en {$subject->name}: {$grade->grade_value}/{$grade->max_grade}",
            [
                'grade_id' => $grade->id,
                'subject_name' => $subject->name,
                'grade_value' => $grade->grade_value,
                'max_grade' => $grade->max_grade,
                'exam_type' => $grade->exam_type,
            ]
        );

        // Notification pour le(s) parent(s)
        $this->notifyParents($student, 'grade_added', [
            'title' => 'Nouvelle note pour votre enfant',
            'message' => "{$student->user->name} a reçu une nouvelle note en {$subject->name}: {$grade->grade_value}/{$grade->max_grade}",
            'data' => [
                'grade_id' => $grade->id,
                'student_name' => $student->user->name,
                'subject_name' => $subject->name,
                'grade_value' => $grade->grade_value,
                'max_grade' => $grade->max_grade,
                'exam_type' => $grade->exam_type,
            ]
        ]);
    }

    /**
     * Notification quand une note est modifiée
     */
    public function notifyGradeUpdated(Grade $grade, $oldValue)
    {
        $student = $grade->student;
        $subject = $grade->subject;
        
        // Notification pour l'étudiant
        $this->create(
            $student->user,
            'grade_updated',
            'Note modifiée',
            "Votre note en {$subject->name} a été modifiée: {$oldValue}/{$grade->max_grade} → {$grade->grade_value}/{$grade->max_grade}",
            [
                'grade_id' => $grade->id,
                'subject_name' => $subject->name,
                'old_value' => $oldValue,
                'new_value' => $grade->grade_value,
                'max_grade' => $grade->max_grade,
            ]
        );

        // Notification pour le(s) parent(s)
        $this->notifyParents($student, 'grade_updated', [
            'title' => 'Note modifiée pour votre enfant',
            'message' => "La note de {$student->user->name} en {$subject->name} a été modifiée: {$oldValue} → {$grade->grade_value}/{$grade->max_grade}",
            'data' => [
                'grade_id' => $grade->id,
                'student_name' => $student->user->name,
                'subject_name' => $subject->name,
                'old_value' => $oldValue,
                'new_value' => $grade->grade_value,
                'max_grade' => $grade->max_grade,
            ]
        ]);
    }

    /**
     * Notification quand une note est supprimée
     */
    public function notifyGradeDeleted(Grade $grade)
    {
        $student = $grade->student;
        $subject = $grade->subject;
        
        // Notification pour l'étudiant
        $this->create(
            $student->user,
            'grade_deleted',
            'Note supprimée',
            "Votre note en {$subject->name} ({$grade->grade_value}/{$grade->max_grade}) a été supprimée",
            [
                'subject_name' => $subject->name,
                'grade_value' => $grade->grade_value,
                'max_grade' => $grade->max_grade,
                'exam_type' => $grade->exam_type,
            ]
        );

        // Notification pour le(s) parent(s)
        $this->notifyParents($student, 'grade_deleted', [
            'title' => 'Note supprimée pour votre enfant',
            'message' => "La note de {$student->user->name} en {$subject->name} ({$grade->grade_value}/{$grade->max_grade}) a été supprimée",
            'data' => [
                'student_name' => $student->user->name,
                'subject_name' => $subject->name,
                'grade_value' => $grade->grade_value,
                'max_grade' => $grade->max_grade,
            ]
        ]);
    }

    /**
     * Notification quand une absence est marquée
     */
    public function notifyAbsenceMarked(Attendance $attendance)
    {
        $student = $attendance->student;
        $statusText = $this->getStatusText($attendance->status);
        $date = $attendance->date->format('d/m/Y');
        
        // Notification pour l'étudiant
        $this->create(
            $student->user,
            'absence_marked',
            'Présence enregistrée',
            "Votre présence du {$date} a été enregistrée: {$statusText}",
            [
                'attendance_id' => $attendance->id,
                'status' => $attendance->status,
                'date' => $date,
                'reason' => $attendance->reason,
            ]
        );

        // Notification pour les parents (seulement si absent ou retard)
        if (in_array($attendance->status, ['absent', 'late'])) {
            $this->notifyParents($student, 'absence_marked', [
                'title' => $attendance->status === 'absent' ? 'Absence de votre enfant' : 'Retard de votre enfant',
                'message' => "{$student->user->name} a été marqué(e) {$statusText} le {$date}",
                'data' => [
                    'attendance_id' => $attendance->id,
                    'student_name' => $student->user->name,
                    'status' => $attendance->status,
                    'date' => $date,
                    'reason' => $attendance->reason,
                ]
            ]);
        }
    }

    /**
     * Notification quand une présence est modifiée
     */
    public function notifyAttendanceUpdated(Attendance $attendance, $oldStatus)
    {
        $student = $attendance->student;
        $oldStatusText = $this->getStatusText($oldStatus);
        $newStatusText = $this->getStatusText($attendance->status);
        $date = $attendance->date->format('d/m/Y');
        
        // Notification pour l'étudiant
        $this->create(
            $student->user,
            'attendance_updated',
            'Présence modifiée',
            "Votre statut de présence du {$date} a été modifié: {$oldStatusText} → {$newStatusText}",
            [
                'attendance_id' => $attendance->id,
                'old_status' => $oldStatus,
                'new_status' => $attendance->status,
                'date' => $date,
            ]
        );

        // Notification pour les parents
        $this->notifyParents($student, 'attendance_updated', [
            'title' => 'Présence modifiée pour votre enfant',
            'message' => "Le statut de présence de {$student->user->name} du {$date} a été modifié: {$oldStatusText} → {$newStatusText}",
            'data' => [
                'attendance_id' => $attendance->id,
                'student_name' => $student->user->name,
                'old_status' => $oldStatus,
                'new_status' => $attendance->status,
                'date' => $date,
            ]
        ]);
    }

    /**
     * Envoyer une notification personnalisée
     */
    public function sendCustomNotification(User $user, string $title, string $message, string $type = 'message', array $data = [])
    {
        return $this->create($user, $type, $title, $message, $data);
    }

    /**
     * Notifier les parents d'un étudiant
     */
    private function notifyParents(Student $student, string $type, array $params)
    {
        // Vérifier si l'étudiant a un parent lié
        if ($student->parent && $student->parent->user) {
            $this->create(
                $student->parent->user,
                $type,
                $params['title'],
                $params['message'],
                $params['data']
            );
        }
    }

    /**
     * Obtenir le texte du statut de présence
     */
    private function getStatusText(string $status): string
    {
        return match($status) {
            'present' => 'Présent(e)',
            'absent' => 'Absent(e)',
            'late' => 'En retard',
            'excused' => 'Absent(e) justifié(e)',
            default => $status,
        };
    }

    /**
     * Notifier tous les utilisateurs d'un rôle spécifique
     */
    public function notifyByRole(string $role, string $type, string $title, string $message, array $data = [])
    {
        $users = User::where('role', $role)->get();
        
        foreach ($users as $user) {
            $this->create($user, $type, $title, $message, $data);
        }
    }

    /**
     * Notifier tous les étudiants d'une classe
     */
    public function notifyClassStudents(int $classId, string $type, string $title, string $message, array $data = [])
    {
        $students = Student::where('class_id', $classId)->with('user')->get();
        
        foreach ($students as $student) {
            $this->create($student->user, $type, $title, $message, $data);
        }
    }
}
