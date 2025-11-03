@extends('layouts.modern')

@section('title', 'Tableau de Bord - Étudiant')
@section('breadcrumb', 'Mon Tableau de Bord')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon Tableau de Bord</h1>
        <p class="mt-2 text-sm text-gray-600">Bienvenue, {{ auth()->user()->name }}</p>
    </div>

    <!-- Student Info Card -->
    <div class="mb-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 p-8 text-white shadow-lg">
        <div class="flex items-center">
            <div class="h-24 w-24 rounded-full bg-white/20 flex items-center justify-center text-4xl font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="ml-6 flex-1">
                <h2 class="text-2xl font-bold">{{ auth()->user()->name }}</h2>
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
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Average Grade Card -->
        <a href="{{ route('etudiant.grades.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-100">Moyenne Générale</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ number_format($average ?? 0, 2) }}/20</p>
                        <p class="mt-2 text-xs text-purple-100">
                            <i class="fas fa-arrow-right mr-1"></i>
                            Voir mes notes
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-chart-line text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Attendance Rate Card -->
        <a href="{{ route('etudiant.attendance.index') }}" class="stat-card block">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-100">Taux de Présence</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ number_format($attendanceRate, 1) }}%</p>
                        <p class="mt-2 text-xs text-green-100">
                            <i class="fas fa-arrow-right mr-1"></i>
                            Voir mes absences
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

        <!-- Subjects Card -->
        <div class="stat-card">
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-pink-500 to-rose-700 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-pink-100">Matières</p>
                        <p class="mt-2 text-4xl font-bold text-white">{{ $subjects->count() }}</p>
                        <p class="mt-2 text-xs text-pink-100">
                            Matières suivies
                        </p>
                    </div>
                    <div class="rounded-full bg-white/20 p-4">
                        <i class="fas fa-book text-3xl text-white"></i>
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
                <p class="text-sm text-gray-500">Vos dernières notes</p>
            </div>
            <div class="relative h-64">
                <canvas id="gradesChart"></canvas>
            </div>
        </div>

        <!-- Attendance Pie Chart -->
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Statistiques de Présence</h3>
                <p class="text-sm text-gray-500">Répartition de vos présences</p>
            </div>
            <div class="relative h-64">
                <canvas id="attendanceChart"></canvas>
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
                        <p class="text-sm text-gray-500">Vos dernières évaluations</p>
                    </div>
                    <a href="{{ route('etudiant.grades.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($grades->take(5) as $grade)
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
                        <h3 class="text-lg font-semibold text-gray-900">Présences Récentes</h3>
                        <p class="text-sm text-gray-500">Vos derniers enregistrements</p>
                    </div>
                    <a href="{{ route('etudiant.attendance.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($attendance->take(5) as $record)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $record->subject->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $record->date->format('d/m/Y') }}</p>
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

    <!-- My Subjects -->
    @if($subjects->count() > 0)
    <div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Mes Matières</h3>
            <p class="text-sm text-gray-500">Matières que je suis cette année</p>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($subjects as $subject)
                <div class="rounded-lg border-2 border-gray-200 p-4 hover:border-indigo-500 hover:bg-indigo-50 transition-all">
                    <div class="flex items-center">
                        <div class="rounded-full bg-indigo-100 p-3">
                            <i class="fas fa-book text-indigo-600"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $subject->name }}</h4>
                            <p class="text-xs text-gray-500">Coefficient: {{ $subject->coefficient }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Grades Evolution Chart
    const gradesCtx = document.getElementById('gradesChart').getContext('2d');
    const gradesData = @json($grades->take(10)->reverse()->values());
    
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
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 20,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });

    // Attendance Pie Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceData = @json($attendance);
    const presentCount = attendanceData.filter(a => a.status === 'present').length;
    const absentCount = attendanceData.filter(a => a.status === 'absent').length;
    const lateCount = attendanceData.filter(a => a.status === 'late').length;
    const excusedCount = attendanceData.filter(a => a.status === 'excused').length;
    
    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Présent', 'Absent', 'Retard', 'Excusé'],
            datasets: [{
                data: [presentCount, absentCount, lateCount, excusedCount],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(59, 130, 246, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12 },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12
                }
            },
            cutout: '60%'
        }
    });
</script>
@endpush
@endsection
