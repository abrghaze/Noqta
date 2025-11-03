@extends('layouts.modern')

@section('title', 'Détails de la Matière')
@section('breadcrumb', 'Matière - ' . $subject->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $subject->name }}</h1>
                <p class="mt-2 text-sm text-gray-600">{{ $subject->class->name }} - Coefficient: {{ $subject->coefficient }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('enseignant.grades.create') }}?subject_id={{ $subject->id }}" 
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter Note
                </a>
            </div>
        </div>
    </div>

    <!-- Subject Statistics -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 mb-8">
        <div class="rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Étudiants</p>
                    <p class="mt-2 text-4xl font-bold">{{ $subject->class->students->count() }}</p>
                </div>
                <i class="fas fa-users text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Moyenne</p>
                    <p class="mt-2 text-4xl font-bold">
                        @php
                            $average = $grades->avg('grade_value');
                        @endphp
                        {{ number_format($average ?? 0, 2) }}
                    </p>
                </div>
                <i class="fas fa-chart-line text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-blue-500 to-cyan-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Notes Saisies</p>
                    <p class="mt-2 text-4xl font-bold">{{ $grades->count() }}</p>
                </div>
                <i class="fas fa-clipboard-list text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-pink-500 to-rose-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Coefficient</p>
                    <p class="mt-2 text-4xl font-bold">{{ $subject->coefficient }}</p>
                </div>
                <i class="fas fa-star text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Students Performance Table -->
    <div class="rounded-xl bg-white shadow-sm overflow-hidden mb-8">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Performance des Étudiants</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nombre de Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Moyenne</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Dernière Note</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($subject->class->students as $student)
                        @php
                            $studentGrades = $grades->where('student_id', $student->id);
                            $studentAverage = $studentGrades->avg('grade_value');
                            $lastGrade = $studentGrades->sortByDesc('date')->first();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->matricule }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                {{ $studentGrades->count() }} notes
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($studentGrades->count() > 0)
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $studentAverage >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ number_format($studentAverage, 2) }}/20
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">Aucune note</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                @if($lastGrade)
                                    {{ $lastGrade->grade_value }}/{{ $lastGrade->max_grade }} ({{ $lastGrade->date->format('d/m/Y') }})
                                @else
                                    -
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('enseignant.grades.create') }}?student_id={{ $student->id }}&subject_id={{ $subject->id }}" 
                                   class="rounded-lg bg-indigo-50 p-2 text-indigo-600 hover:bg-indigo-100 transition-colors inline-flex items-center" 
                                   title="Ajouter note">
                                    <i class="fas fa-plus mr-1"></i>
                                    Ajouter
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-user-graduate text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900">Aucun étudiant dans cette classe</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Grades -->
    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Notes Récentes</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Commentaire</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($grades->sortByDesc('date')->take(10) as $grade)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($grade->student->user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $grade->student->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $grade->grade_value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-lg bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                    {{ $grade->exam_type }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $grade->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                {{ $grade->comment ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900">Aucune note enregistrée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
