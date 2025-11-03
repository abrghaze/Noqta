@extends('layouts.app')

@section('title', 'Mes Notes')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Mes Notes</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4"><i class="fas fa-clipboard-list"></i> Mes Notes</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ number_format($average, 2) }}</h2>
                    <p class="mb-0">Moyenne Générale</p>
                    <small>/20</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $grades->count() }}</h2>
                    <p class="mb-0">Total Notes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $grades->max('grade_value') ?? 0 }}</h2>
                    <p class="mb-0">Note la Plus Haute</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $grades->min('grade_value') ?? 0 }}</h2>
                    <p class="mb-0">Note la Plus Basse</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Rechercher</label>
                    <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Matière</label>
                    <select class="form-select" name="subject_id">
                        <option value="">Toutes les matières</option>
                        @foreach($grades->unique('subject_id') as $grade)
                            <option value="{{ $grade->subject_id }}">{{ $grade->subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select class="form-select" name="type">
                        <option value="">Tous les types</option>
                        <option value="Composition">Composition</option>
                        <option value="Devoir">Devoir</option>
                        <option value="Contrôle">Contrôle</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filtrer</button>
                    <a href="{{ route('etudiant.grades.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h5 class="mb-0"><i class="fas fa-list"></i> Toutes mes notes</h5>
            <button class="btn btn-sm btn-light"><i class="fas fa-download"></i> Télécharger le bulletin</button>
        </div>
        <div class="card-body">
            @if($grades->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Matière</th>
                                <th>Note</th>
                                <th>Type</th>
                                <th>Enseignant</th>
                                <th>Commentaires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $grade)
                                <tr>
                                    <td>{{ $grade->date->format('d/m/Y') }}</td>
                                    <td><strong>{{ $grade->subject->name }}</strong></td>
                                    <td>
                                        <span class="badge fs-6 bg-{{ $grade->grade_value >= 15 ? 'success' : ($grade->grade_value >= 10 ? 'warning' : 'danger') }}">
                                            {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-primary">{{ $grade->exam_type }}</span></td>
                                    <td>{{ $grade->teacher->user->name ?? 'N/A' }}</td>
                                    <td>{{ $grade->comment ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Aucune note enregistrée pour le moment</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Évolution des Notes</h5>
                </div>
                <div class="card-body">
                    <canvas id="gradesChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Performance par Matière</h5>
                </div>
                <div class="card-body">
                    <canvas id="subjectsChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grades Evolution Chart
const gradesCtx = document.getElementById('gradesChart').getContext('2d');
new Chart(gradesCtx, {
    type: 'line',
    data: {
        labels: @json($grades->pluck('date')->map(fn($d) => $d->format('d/m'))),
        datasets: [{
            label: 'Notes',
            data: @json($grades->pluck('grade_value')),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 20
            }
        }
    }
});

// Subjects Performance Chart
const subjectsCtx = document.getElementById('subjectsChart').getContext('2d');
const subjectGrades = @json($grades->groupBy('subject.name')->map(fn($g) => $g->avg('grade_value')));
new Chart(subjectsCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(subjectGrades),
        datasets: [{
            label: 'Moyenne',
            data: Object.values(subjectGrades),
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 20
            }
        }
    }
});
</script>
@endpush
@endsection
