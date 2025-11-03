@extends('layouts.app')

@section('title', 'Mes Absences')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Mes Absences</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4"><i class="fas fa-calendar-check"></i> Mes Présences et Absences</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ number_format($attendanceRate, 1) }}%</h2>
                    <p class="mb-0">Taux de Présence</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $attendance->count() }}</h2>
                    <p class="mb-0">Total Jours</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $attendance->where('status', 'present')->count() }}</h2>
                    <p class="mb-0">Jours Présents</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $attendance->where('status', 'absent')->count() }}</h2>
                    <p class="mb-0">Jours Absents</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Matière</label>
                    <select class="form-select" name="subject_id">
                        <option value="">Toutes les matières</option>
                        @foreach($attendance->unique('subject_id') as $record)
                            <option value="{{ $record->subject_id }}">{{ $record->subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="">Tous</option>
                        <option value="present">Présent</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Du</label>
                    <input type="date" class="form-control" name="date_from">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Au</label>
                    <input type="date" class="form-control" name="date_to">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filtrer</button>
                    <a href="{{ route('etudiant.attendance.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Attendance Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Historique des Présences</h5>
                </div>
                <div class="card-body">
                    @if($attendance->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Matière</th>
                                        <th>Statut</th>
                                        <th>Raison</th>
                                        <th>Justification</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendance as $record)
                                        <tr>
                                            <td>{{ $record->date->format('d/m/Y') }}</td>
                                            <td><strong>{{ $record->subject->name }}</strong></td>
                                            <td>
                                                <span class="badge fs-6 bg-{{ $record->status == 'present' ? 'success' : 'danger' }}">
                                                    <i class="fas fa-{{ $record->status == 'present' ? 'check' : 'times' }}"></i>
                                                    {{ $record->status == 'present' ? 'Présent' : 'Absent' }}
                                                </span>
                                            </td>
                                            <td>{{ $record->reason ?? '-' }}</td>
                                            <td>{{ $record->parent_justification ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Aucun enregistrement de présence</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Charts Column -->
        <div class="col-lg-4">
            <!-- Attendance Pie Chart -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Répartition</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendancePieChart"></canvas>
                </div>
            </div>

            <!-- Attendance by Subject -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Par Matière</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Matière</th>
                                    <th>Taux</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendance->groupBy('subject_id') as $subjectId => $records)
                                    @php
                                        $subject = $records->first()->subject;
                                        $rate = ($records->where('status', 'present')->count() / $records->count()) * 100;
                                    @endphp
                                    <tr>
                                        <td>{{ $subject->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $rate >= 80 ? 'success' : ($rate >= 60 ? 'warning' : 'danger') }}">
                                                {{ number_format($rate, 0) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Attendance Pie Chart
const pieCtx = document.getElementById('attendancePieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Présent', 'Absent'],
        datasets: [{
            data: [
                {{ $attendance->where('status', 'present')->count() }},
                {{ $attendance->where('status', 'absent')->count() }}
            ],
            backgroundColor: [
                'rgba(16, 185, 129, 0.8)',
                'rgba(239, 68, 68, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush
@endsection
