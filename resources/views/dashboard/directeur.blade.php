@extends('layouts.app')

@section('title', 'Tableau de Bord - Directeur')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2"></i> Tableau de Bord - Directeur</h2>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Étudiants</h6>
                            <h2 class="card-title mb-0">{{ $totalStudents }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Enseignants</h6>
                            <h2 class="card-title mb-0">{{ $totalTeachers }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Classes</h6>
                            <h2 class="card-title mb-0">{{ $totalClasses }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Matières</h6>
                            <h2 class="card-title mb-0">{{ $totalSubjects }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-book-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Today -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-calendar-check"></i> Présence Aujourd'hui</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-graph-up"></i> Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="text-success">{{ $presentToday }}</h3>
                            <p class="text-muted">Présents</p>
                        </div>
                        <div class="col-6">
                            <h3 class="text-danger">{{ $absentToday }}</h3>
                            <p class="text-muted">Absents</p>
                        </div>
                    </div>
                    <hr>
                    <p class="text-center mb-0">
                        Taux de présence: 
                        <strong class="text-primary">
                            {{ $presentToday + $absentToday > 0 ? round(($presentToday / ($presentToday + $absentToday)) * 100, 1) : 0 }}%
                        </strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Grades -->
    <div class="row g-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-journal-text"></i> Notes Récentes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Matière</th>
                                    <th>Note</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentGrades as $grade)
                                    <tr>
                                        <td>{{ $grade->student->user->name }}</td>
                                        <td>{{ $grade->subject->name }}</td>
                                        <td>
                                            <span class="badge {{ $grade->grade_value >= 10 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                            </span>
                                        </td>
                                        <td>{{ $grade->exam_type }}</td>
                                        <td>{{ $grade->date->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Aucune note récente</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Attendance Chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Présents', 'Absents'],
            datasets: [{
                data: [{{ $presentToday }}, {{ $absentToday }}],
                backgroundColor: ['#38ef7d', '#f5576c'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection
