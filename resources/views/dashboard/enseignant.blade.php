@extends('layouts.app')

@section('title', 'Tableau de Bord - Enseignant')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2"></i> Tableau de Bord - Enseignant</h2>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-2">Mes Classes</h6>
                            <h2 class="card-title mb-0">{{ $classes->count() }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-door-open-fill"></i>
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
                            <h6 class="card-subtitle mb-2">Matières</h6>
                            <h2 class="card-title mb-0">{{ $subjects->count() }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-book-fill"></i>
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
                            <h6 class="card-subtitle mb-2">Étudiants</h6>
                            <h2 class="card-title mb-0">{{ $students->count() }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes and Subjects -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-door-open"></i> Mes Classes</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($classes as $class)
                            <a href="{{ route('enseignant.classes.show', $class) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $class->name }}</h6>
                                        <small class="text-muted">{{ $class->students_count }} étudiants</small>
                                    </div>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </a>
                        @empty
                            <p class="text-muted text-center py-3">Aucune classe assignée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-book"></i> Mes Matières</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($subjects as $subject)
                            <a href="{{ route('enseignant.subjects.show', $subject) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $subject->name }}</h6>
                                        <small class="text-muted">{{ $subject->class->name }} - Coef: {{ $subject->coefficient }}</small>
                                    </div>
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </a>
                        @empty
                            <p class="text-muted text-center py-3">Aucune matière assignée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="row g-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-calendar-check"></i> Absences Récentes</h5>
                    <a href="{{ route('enseignant.attendance.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> Marquer Présence
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Matière</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Raison</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendance as $attendance)
                                    <tr>
                                        <td>{{ $attendance->student->user->name }}</td>
                                        <td>{{ $attendance->subject->name }}</td>
                                        <td>
                                            @if($attendance->status === 'present')
                                                <span class="badge bg-success">Présent</span>
                                            @elseif($attendance->status === 'absent')
                                                <span class="badge bg-danger">Absent</span>
                                            @elseif($attendance->status === 'late')
                                                <span class="badge bg-warning">Retard</span>
                                            @else
                                                <span class="badge bg-info">Excusé</span>
                                            @endif
                                        </td>
                                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                        <td>{{ $attendance->reason ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Aucune absence récente</td>
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
@endsection
