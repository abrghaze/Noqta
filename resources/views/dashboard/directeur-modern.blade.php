@extends('layouts.modern')

@section('title', 'Tableau de Bord - Directeur')
@section('breadcrumb', 'Tableau de Bord')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord - Directeur</h1>
        <p class="mt-2 text-sm text-gray-600">Vue d'ensemble de l'établissement</p>
    </div>

    <!-- Clickable Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Students Card -->
        <a href="{{ route('directeur.users.index') }}?role=etudiant" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-100">Étudiants</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $totalStudents }}</p>
                        <p class="mt-2 text-xs text-purple-100">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Voir tous les étudiants
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-user-graduate text-3xl text-white"></i>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10"></div>
            </div>
        </a>

        <!-- Teachers Card -->
        <a href="{{ route('directeur.users.index') }}?role=enseignant" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Enseignants</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $totalTeachers }}</p>
                        <p class="mt-2 text-xs text-green-100">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Voir tous les enseignants
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-chalkboard-teacher text-3xl text-white"></i>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10"></div>
            </div>
        </a>

        <!-- Classes Card -->
        <a href="{{ route('directeur.classes.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-pink-500 to-rose-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-pink-100">Classes</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $totalClasses }}</p>
                        <p class="mt-2 text-xs text-pink-100">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Gérer les classes
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-door-open text-3xl text-white"></i>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10"></div>
            </div>
        </a>

        <!-- Subjects Card -->
        <a href="{{ route('directeur.subjects.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-cyan-500 to-blue-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-cyan-100">Matières</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $totalSubjects }}</p>
                        <p class="mt-2 text-xs text-cyan-100">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Gérer les matières
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-book text-3xl text-white"></i>
                    </div>
                </div>
                <div class="absolute -right-4 -bottom-4 h-24 w-24 rounded-full bg-white/10"></div>
            </div>
        </a>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
        <!-- Attendance Chart -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Présence Aujourd'hui</h3>
                    <p class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</p>
                </div>
                <div class="rounded-lg bg-indigo-50 p-2">
                    <i class="fas fa-calendar-check text-indigo-600"></i>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="attendanceChart"></canvas>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="rounded-lg bg-green-50 p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $presentToday }}</p>
                    <p class="text-sm text-green-700">Présents</p>
                </div>
                <div class="rounded-lg bg-red-50 p-4 text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $absentToday }}</p>
                    <p class="text-sm text-red-700">Absents</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Statistiques Globales</h3>
                    <p class="text-sm text-gray-500">Vue d'ensemble</p>
                </div>
                <div class="rounded-lg bg-purple-50 p-2">
                    <i class="fas fa-chart-pie text-purple-600"></i>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Attendance Rate -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Taux de Présence</span>
                        <span class="text-sm font-bold text-indigo-600">
                            {{ $presentToday + $absentToday > 0 ? round(($presentToday / ($presentToday + $absentToday)) * 100, 1) : 0 }}%
                        </span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-gradient-to-r from-green-400 to-emerald-500" 
                             style="width: {{ $presentToday + $absentToday > 0 ? round(($presentToday / ($presentToday + $absentToday)) * 100, 1) : 0 }}%"></div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center">
                            <div class="rounded-full bg-purple-100 p-2">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Total Utilisateurs</p>
                                <p class="text-lg font-bold text-gray-900">{{ $totalStudents + $totalTeachers + 1 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center">
                            <div class="rounded-full bg-blue-100 p-2">
                                <i class="fas fa-clipboard-list text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs text-gray-500">Notes Récentes</p>
                                <p class="text-lg font-bold text-gray-900">{{ $recentGrades->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-700 mb-3">Actions Rapides</p>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('directeur.users.create') }}" class="flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 p-3 text-center hover:border-indigo-500 hover:bg-indigo-50 transition-all">
                            <i class="fas fa-plus mr-2 text-indigo-600"></i>
                            <span class="text-sm font-medium text-gray-700">Ajouter Utilisateur</span>
                        </a>
                        <a href="{{ route('directeur.classes.create') }}" class="flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 p-3 text-center hover:border-green-500 hover:bg-green-50 transition-all">
                            <i class="fas fa-plus mr-2 text-green-600"></i>
                            <span class="text-sm font-medium text-gray-700">Ajouter Classe</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Grades Table -->
    <div class="rounded-xl bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Notes Récentes</h3>
                    <p class="text-sm text-gray-500">Dernières évaluations enregistrées</p>
                </div>
                <button class="rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-100 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Exporter
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($recentGrades as $grade)
                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($grade->student->user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $grade->student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $grade->student->matricule }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $grade->subject->name }}</div>
                                <div class="text-sm text-gray-500">{{ $grade->subject->class->name }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $grade->grade_value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $grade->exam_type }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $grade->date->format('d/m/Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-500">Aucune note récente</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Attendance Chart with better styling
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Présents', 'Absents'],
            datasets: [{
                data: [{{ $presentToday }}, {{ $absentToday }}],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 14,
                            family: 'Inter'
                        },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            const total = {{ $presentToday + $absentToday }};
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush
@endsection
