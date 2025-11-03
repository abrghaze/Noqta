@extends('layouts.modern')

@section('title', 'Tableau de Bord - Parent')
@section('breadcrumb', 'Suivi de Mon Enfant')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Suivi de Mon Enfant</h1>
        <p class="mt-2 text-sm text-gray-600">Bienvenue, {{ auth()->user()->name }}</p>
    </div>

    <!-- Child Info Card -->
    <div class="mb-8 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 p-8 text-white shadow-lg">
        <div class="flex items-center">
            <div class="h-24 w-24 rounded-full bg-white/20 flex items-center justify-center text-4xl font-bold">
                {{ strtoupper(substr($student->user->name, 0, 2)) }}
            </div>
            <div class="ml-6 flex-1">
                <h2 class="text-2xl font-bold">{{ $student->user->name }}</h2>
                <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-3">
                    <div class="flex items-center">
                        <i class="fas fa-door-open mr-2"></i>
                        <span>{{ $class ? $class->name : 'Non assigné' }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-id-card mr-2"></i>
                        <span>{{ $student->matricule }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope mr-2"></i>
                        <span>{{ $student->user->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Alerts -->
    @if($average < 10)
        <div class="mb-6 rounded-lg bg-red-50 p-4 border-l-4 border-red-400">
            <div class="flex">
                <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Attention - Moyenne Faible</h3>
                    <p class="mt-1 text-sm text-red-700">
                        La moyenne de votre enfant ({{ number_format($average, 2) }}/20) est inférieure à 10. 
                        Nous vous recommandons de prendre contact avec les enseignants.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($attendanceRate < 80)
        <div class="mb-6 rounded-lg bg-yellow-50 p-4 border-l-4 border-yellow-400">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-yellow-400 text-xl"></i>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Alerte - Taux de Présence Bas</h3>
                    <p class="mt-1 text-sm text-yellow-700">
                        Le taux de présence de votre enfant ({{ number_format($attendanceRate, 1) }}%) est inférieur à 80%. 
                        Veuillez vérifier les absences.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Average Grade Card -->
        <a href="{{ route('parent.grades.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-100">Moyenne Générale</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ number_format($average ?? 0, 2) }}/20</p>
                        <p class="mt-2 text-xs text-purple-100">
                            <i class="fas fa-arrow-right mr-1"></i>
                            Voir les notes
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-chart-line text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Attendance Rate Card -->
        <a href="{{ route('parent.attendance.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-{{ $attendanceRate >= 80 ? 'green' : 'orange' }}-500 to-{{ $attendanceRate >= 80 ? 'emerald' : 'red' }}-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-white opacity-90">Taux de Présence</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ number_format($attendanceRate, 1) }}%</p>
                        <p class="mt-2 text-xs text-white opacity-90">
                            <i class="fas fa-arrow-right mr-1"></i>
                            Voir les absences
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-calendar-check text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Total Grades Card -->
        <div class="stat-card">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-500 to-cyan-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-100">Total Notes</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $grades->count() }}</p>
                        <p class="mt-2 text-xs text-blue-100">
                            Notes enregistrées
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-clipboard-list text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Good Grades Card -->
        <div class="stat-card">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Notes ≥ 10</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $grades->where('grade_value', '>=', 10)->count() }}</p>
                        <p class="mt-2 text-xs text-green-100">
                            Sur {{ $grades->count() }} notes
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-star text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
        <!-- Grades Evolution Chart -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Évolution des Notes</h3>
                <p class="text-sm text-gray-500">Progression de votre enfant</p>
            </div>
            <div class="relative h-64">
                <canvas id="gradesChart"></canvas>
            </div>
        </div>

        <!-- Subject Performance Chart -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Performance par Matière</h3>
                <p class="text-sm text-gray-500">Moyennes par matière</p>
            </div>
            <div class="relative h-64">
                <canvas id="subjectsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Grades and Attendance -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Grades -->
        <div class="rounded-xl bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Notes Récentes</h3>
                        <p class="text-sm text-gray-500">Dernières évaluations</p>
                    </div>
                    <a href="{{ route('parent.grades.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($grades->take(8) as $grade)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $grade->subject->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $grade->exam_type }} - {{ $grade->date->format('d/m/Y') }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $grade->grade_value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $grade->grade_value }}/{{ $grade->max_grade }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Aucune note disponible</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="rounded-xl bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Absences Récentes</h3>
                        <p class="text-sm text-gray-500">Derniers enregistrements</p>
                    </div>
                    <a href="{{ route('parent.attendance.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($attendance->take(8) as $record)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $record->subject->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $record->date->format('d/m/Y') }}</p>
                                @if($record->reason)
                                    <p class="text-xs text-gray-600 mt-1">{{ $record->reason }}</p>
                                @endif
                            </div>
                            @if($record->status === 'present')
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">
                                    <i class="fas fa-check mr-1"></i> Présent
                                </span>
                            @elseif($record->status === 'absent')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800">
                                    <i class="fas fa-times mr-1"></i> Absent
                                </span>
                            @elseif($record->status === 'late')
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Retard
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">
                                    <i class="fas fa-info mr-1"></i> Excusé
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-calendar-check text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Aucun enregistrement</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Grades Evolution Chart
    const gradesCtx = document.getElementById('gradesChart').getContext('2d');
    const gradesData = @json($grades->take(15)->reverse()->values());
    
    new Chart(gradesCtx, {
        type: 'line',
        data: {
            labels: gradesData.map(g => new Date(g.date).toLocaleDateString('fr-FR')),
            datasets: [{
                label: 'Notes',
                data: gradesData.map(g => g.grade_value),
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 20,
                    ticks: { stepSize: 5 }
                }
            }
        }
    });

    // Subject Performance Chart
    const subjectsCtx = document.getElementById('subjectsChart').getContext('2d');
    const allGrades = @json($grades);
    
    // Group grades by subject and calculate averages
    const subjectAverages = {};
    allGrades.forEach(grade => {
        if (!subjectAverages[grade.subject.name]) {
            subjectAverages[grade.subject.name] = [];
        }
        subjectAverages[grade.subject.name].push(grade.grade_value);
    });
    
    const subjects = Object.keys(subjectAverages);
    const averages = subjects.map(subject => {
        const grades = subjectAverages[subject];
        return grades.reduce((a, b) => a + b, 0) / grades.length;
    });
    
    new Chart(subjectsCtx, {
        type: 'bar',
        data: {
            labels: subjects,
            datasets: [{
                label: 'Moyenne',
                data: averages,
                backgroundColor: averages.map(avg => avg >= 10 ? 'rgba(34, 197, 94, 0.8)' : 'rgba(239, 68, 68, 0.8)'),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return 'Moyenne: ' + context.parsed.y.toFixed(2) + '/20';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 20,
                    ticks: { stepSize: 5 }
                }
            }
        }
    });
</script>
@endpush
@endsection
