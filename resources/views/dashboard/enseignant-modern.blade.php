@extends('layouts.modern')

@section('title', 'Tableau de Bord - Enseignant')
@section('breadcrumb', 'Tableau de Bord')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord - Enseignant</h1>
        <p class="mt-2 text-sm text-gray-600">Bienvenue, {{ auth()->user()->name }}</p>
    </div>

    <!-- Clickable Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <!-- My Classes Card -->
        <a href="{{ route('enseignant.classes.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-100">Mes Classes</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $classes->count() }}</p>
                        <p class="mt-2 text-xs text-purple-100">
                            <i class="fas fa-arrow-right mr-1"></i>
                            Voir mes classes
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-door-open text-3xl text-white"></i>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10"></div>
            </div>
        </a>

        <!-- My Subjects Card -->
        <a href="{{ route('enseignant.subjects.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Matières</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $subjects->count() }}</p>
                        <p class="mt-2 text-xs text-green-100">
                            <i class="fas fa-arrow-right mr-1"></i>
                            Voir mes matières
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-book text-3xl text-white"></i>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10"></div>
            </div>
        </a>

        <!-- My Students Card -->
        <a href="{{ route('enseignant.students.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-pink-500 to-rose-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-pink-100">Étudiants</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $students->count() }}</p>
                        <p class="mt-2 text-xs text-pink-100">
                            <i class="fas fa-arrow-right mr-1"></i>
                            Voir mes étudiants
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-user-graduate text-3xl text-white"></i>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10"></div>
            </div>
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('enseignant.attendance.create') }}" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-4">
                    <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Marquer Présence</h3>
                    <p class="text-sm text-gray-500">Enregistrer les présences du jour</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
            </div>
        </a>

        <a href="{{ route('enseignant.grades.create') }}" class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-4">
                    <i class="fas fa-plus-circle text-2xl text-green-600"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Ajouter des Notes</h3>
                    <p class="text-sm text-gray-500">Saisir les notes des étudiants</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
            </div>
        </a>
    </div>

    <!-- My Classes and Subjects -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
        <!-- My Classes -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Mes Classes</h3>
                    <p class="text-sm text-gray-500">Classes que j'enseigne</p>
                </div>
                <a href="{{ route('enseignant.classes.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($classes as $class)
                    <a href="{{ route('enseignant.classes.show', $class) }}" class="block rounded-lg border border-gray-200 p-4 hover:border-indigo-500 hover:bg-indigo-50 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="rounded-full bg-purple-100 p-2">
                                    <i class="fas fa-door-open text-purple-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $class->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $class->students_count }} étudiants</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-door-open text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Aucune classe assignée</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- My Subjects -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Mes Matières</h3>
                    <p class="text-sm text-gray-500">Matières que j'enseigne</p>
                </div>
                <a href="{{ route('enseignant.subjects.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($subjects as $subject)
                    <a href="{{ route('enseignant.subjects.show', $subject) }}" class="block rounded-lg border border-gray-200 p-4 hover:border-green-500 hover:bg-green-50 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="rounded-full bg-green-100 p-2">
                                    <i class="fas fa-book text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $subject->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $subject->class->name }} - Coef: {{ $subject->coefficient }}</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-book text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Aucune matière assignée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="rounded-xl bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Absences Récentes</h3>
                    <p class="text-sm text-gray-500">Derniers enregistrements de présence</p>
                </div>
                <a href="{{ route('enseignant.attendance.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Marquer Présence
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Raison</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($recentAttendance as $attendance)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($attendance->student->user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $attendance->student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $attendance->student->class->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                {{ $attendance->subject->name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($attendance->status === 'present')
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                        <i class="fas fa-check mr-1"></i> Présent
                                    </span>
                                @elseif($attendance->status === 'absent')
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                        <i class="fas fa-times mr-1"></i> Absent
                                    </span>
                                @elseif($attendance->status === 'late')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Retard
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                        <i class="fas fa-info mr-1"></i> Excusé
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $attendance->date->format('d/m/Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $attendance->reason ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-500">Aucune absence récente</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
