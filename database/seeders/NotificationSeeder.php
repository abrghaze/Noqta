<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'etudiant')->get();
        $parents = User::where('role', 'parent')->get();
        $teachers = User::where('role', 'enseignant')->get();
        $directors = User::where('role', 'directeur')->get();

        // Create notifications for students
        foreach ($students->take(5) as $student) {
            // Grade notification (unread)
            Notification::create([
                'user_id' => $student->id,
                'type' => 'grade_added',
                'title' => 'Nouvelle note ajoutée',
                'message' => 'Vous avez reçu une nouvelle note en Mathématiques: 16/20',
                'data' => ['subject' => 'Mathématiques', 'grade' => 16],
                'read_at' => null,
            ]);

            // Absence notification (read)
            Notification::create([
                'user_id' => $student->id,
                'type' => 'absence_marked',
                'title' => 'Absence marquée',
                'message' => 'Vous avez été marqué absent en Physique le 25/10/2025',
                'data' => ['subject' => 'Physique', 'date' => '2025-10-25'],
                'read_at' => now()->subDays(2),
            ]);

            // System message (unread)
            Notification::create([
                'user_id' => $student->id,
                'type' => 'system',
                'title' => 'Bienvenue sur Noqta',
                'message' => 'Consultez vos notes et absences en temps réel!',
                'data' => null,
                'read_at' => null,
            ]);
        }

        // Create notifications for parents
        foreach ($parents->take(5) as $parent) {
            Notification::create([
                'user_id' => $parent->id,
                'type' => 'grade_added',
                'title' => 'Note de votre enfant',
                'message' => 'Votre enfant a reçu une note en Histoire: 14/20',
                'data' => ['child' => 'Ahmed', 'subject' => 'Histoire', 'grade' => 14],
                'read_at' => null,
            ]);

            Notification::create([
                'user_id' => $parent->id,
                'type' => 'absence_marked',
                'title' => 'Absence de votre enfant',
                'message' => 'Votre enfant était absent en Anglais le 24/10/2025',
                'data' => ['child' => 'Ahmed', 'subject' => 'Anglais', 'date' => '2025-10-24'],
                'read_at' => null,
            ]);
        }

        // Create notifications for teachers
        foreach ($teachers->take(3) as $teacher) {
            Notification::create([
                'user_id' => $teacher->id,
                'type' => 'system',
                'title' => 'Rappel: Saisie des notes',
                'message' => 'N\'oubliez pas de saisir les notes du dernier contrôle',
                'data' => null,
                'read_at' => null,
            ]);
        }

        // Create notifications for directors
        foreach ($directors as $director) {
            Notification::create([
                'user_id' => $director->id,
                'type' => 'system',
                'title' => 'Rapport mensuel disponible',
                'message' => 'Le rapport de performance du mois d\'octobre est disponible',
                'data' => null,
                'read_at' => null,
            ]);

            Notification::create([
                'user_id' => $director->id,
                'type' => 'message',
                'title' => 'Nouvelle demande',
                'message' => 'Un parent a contacté l\'administration',
                'data' => null,
                'read_at' => now()->subHours(3),
            ]);
        }
    }
}
