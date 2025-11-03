@extends('layouts.app')

@section('title', 'Tableau de Bord - Étudiant')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2"></i> Mon Tableau de Bord</h2>
    </div>

    <!-- Student Info Card -->
    <div class="row g-4 mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="avatar-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-radius: 50%; font-size: 2.5rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h4>{{ auth()->user()->name }}</h4>
                            <p class="text-muted mb-2">
                                <i class="bi bi-door-open"></i> Classe: <strong>{{ $class ? $class->name : 'Non assigné' }}</strong>
                            </p>
                            <p class="text-muted mb-2">
                                <i class="bi bi-card-text"></i> Matricule: <strong>{{ $student->matricule }}</strong>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="bi bi-envelope"></i> Email: <strong>{{ auth()->user()->email }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Moyenne Générale</h6>
                            <h2 class="card-title mb-0">{{ number_format($average ?? 0, 2) }}/20</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Taux de Présence</h6>
                            <h2 class="card-title mb-0">{{ number_format($attendanceRate, 1) }}%</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Matières</h6>
                            <h2 class="card-title mb-0">{{ $subjects->count() }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades and Attendance -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-journal-text"></i> Mes Notes Récentes</h5>
                    <a href="{{ route('etudiant.grades.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Matière</th>
                                    <th>Note</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades->take(5) as $grade)
                                    <tr>
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
                                        <td colspan="4" class="text-center text-muted">Aucune note disponible</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-calendar-check"></i> Mes Absences Récentes</h5>
                    <a href="{{ route('etudiant.attendance.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Matière</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendance->take(5) as $record)
                                    <tr>
                                        <td>{{ $record->subject->name }}</td>
                                        <td>
                                            @if($record->status === 'present')
                                                <span class="badge bg-success">Présent</span>
                                            @elseif($record->status === 'absent')
                                                <span class="badge bg-danger">Absent</span>
                                            @elseif($record->status === 'late')
                                                <span class="badge bg-warning">Retard</span>
                                            @else
                                                <span class="badge bg-info">Excusé</span>
                                            @endif
                                        </td>
                                        <td>{{ $record->date->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Aucun enregistrement</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects List -->
    @if($subjects->count() > 0)
    <div class="row g-4 mt-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-book"></i> Mes Matières</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($subjects as $subject)
                            <div class="col-md-4 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $subject->name }}</h6>
                                        <p class="card-text small text-muted mb-0">
                                            Coefficient: {{ $subject->coefficient }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
