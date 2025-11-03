<?php

namespace Database\Seeders;

use App\Models\{User, Student, Teacher, ParentProfile, ClassRoom, Subject, Grade, Attendance};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Director
        $directeur = User::create([
            'name' => 'Directeur Principal',
            'email' => 'directeur@school.com',
            'password' => Hash::make('password'),
            'role' => 'directeur',
        ]);

        // Create Teachers
        $teacher1 = User::create([
            'name' => 'Prof. Jean Dupont',
            'email' => 'jean.dupont@school.com',
            'password' => Hash::make('password'),
            'role' => 'enseignant',
        ]);

        Teacher::create([
            'user_id' => $teacher1->id,
            'specialization' => 'Mathématiques',
            'phone' => '+221 77 123 45 67',
        ]);

        $teacher2 = User::create([
            'name' => 'Prof. Marie Martin',
            'email' => 'marie.martin@school.com',
            'password' => Hash::make('password'),
            'role' => 'enseignant',
        ]);

        Teacher::create([
            'user_id' => $teacher2->id,
            'specialization' => 'Physique-Chimie',
            'phone' => '+221 77 234 56 78',
        ]);

        // Create Classes
        $class1 = ClassRoom::create([
            'name' => 'Terminale S1',
            'teacher_id' => $teacher1->id,
            'description' => 'Classe de Terminale Scientifique 1',
        ]);

        $class2 = ClassRoom::create([
            'name' => 'Première L2',
            'teacher_id' => $teacher2->id,
            'description' => 'Classe de Première Littéraire 2',
        ]);

        // Create Subjects for Class 1
        $math = Subject::create([
            'name' => 'Mathématiques',
            'class_id' => $class1->id,
            'teacher_id' => $teacher1->id,
            'coefficient' => 4,
            'description' => 'Mathématiques avancées',
        ]);

        $physics = Subject::create([
            'name' => 'Physique-Chimie',
            'class_id' => $class1->id,
            'teacher_id' => $teacher2->id,
            'coefficient' => 3,
            'description' => 'Sciences physiques et chimiques',
        ]);

        // Create Subjects for Class 2
        $french = Subject::create([
            'name' => 'Français',
            'class_id' => $class2->id,
            'teacher_id' => $teacher2->id,
            'coefficient' => 4,
            'description' => 'Littérature française',
        ]);

        // Create Parents first
        $parents = [];
        for ($i = 1; $i <= 10; $i++) {
            $parentUser = User::create([
                'name' => "Parent de l'Étudiant $i",
                'email' => "parent$i@school.com",
                'password' => Hash::make('password'),
                'role' => 'parent',
            ]);

            $parent = ParentProfile::create([
                'user_id' => $parentUser->id,
                'relationship' => $i % 2 == 0 ? 'Père' : 'Mère',
                'phone' => '+221 77 ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
            ]);

            $parents[] = $parent;
        }

        // Create Students and link to parents
        $students = [];
        for ($i = 1; $i <= 10; $i++) {
            $studentUser = User::create([
                'name' => "Étudiant $i",
                'email' => "etudiant$i@school.com",
                'password' => Hash::make('password'),
                'role' => 'etudiant',
            ]);

            $student = Student::create([
                'user_id' => $studentUser->id,
                'class_id' => $i <= 5 ? $class1->id : $class2->id,
                'parent_id' => $parents[$i - 1]->id, // Link to parent
                'matricule' => 'STU' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'date_of_birth' => now()->subYears(rand(16, 18))->format('Y-m-d'),
                'phone' => '+221 77 ' . rand(100, 999) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'address' => "Adresse de l'étudiant $i, Dakar",
            ]);

            $students[] = $student;

            // Create Grades for students in Class 1
            if ($i <= 5) {
                Grade::create([
                    'student_id' => $student->id,
                    'subject_id' => $math->id,
                    'grade_value' => rand(10, 20),
                    'max_grade' => 20,
                    'exam_type' => 'Devoir',
                    'date' => now()->subDays(rand(1, 30)),
                    'comment' => 'Bon travail',
                ]);

                Grade::create([
                    'student_id' => $student->id,
                    'subject_id' => $physics->id,
                    'grade_value' => rand(10, 20),
                    'max_grade' => 20,
                    'exam_type' => 'Contrôle',
                    'date' => now()->subDays(rand(1, 30)),
                    'comment' => 'Très bien',
                ]);
            } else {
                // Grades for Class 2
                Grade::create([
                    'student_id' => $student->id,
                    'subject_id' => $french->id,
                    'grade_value' => rand(10, 20),
                    'max_grade' => 20,
                    'exam_type' => 'Composition',
                    'date' => now()->subDays(rand(1, 30)),
                    'comment' => 'Excellent',
                ]);
            }

            // Create Attendance records
            for ($day = 0; $day < 10; $day++) {
                $date = now()->subDays($day);
                $subjects = $i <= 5 ? [$math->id, $physics->id] : [$french->id];

                foreach ($subjects as $subjectId) {
                    Attendance::create([
                        'student_id' => $student->id,
                        'subject_id' => $subjectId,
                        'status' => rand(1, 10) > 2 ? 'present' : 'absent',
                        'date' => $date,
                        'reason' => rand(1, 10) > 8 ? 'Maladie' : null,
                    ]);
                }
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Directeur: directeur@school.com / password');
        $this->command->info('Enseignant: jean.dupont@school.com / password');
        $this->command->info('Étudiant: etudiant1@school.com / password');
        $this->command->info('Parent: parent1@school.com / password');
    }
}
