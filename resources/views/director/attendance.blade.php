@extends('layouts.app')

@section('title', 'Toutes les Présences du Système')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Toutes les Présences</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4"><i class="fas fa-calendar-check"></i> Toutes les Présences du Système</h1>

    <!-- Statistics Dashboard -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-list fa-2x mb-2"></i>
                    <h2 class="mb-0">{{ $attendance->total() }}</h2>
                    <small>Total Enregistrements</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-2x mb-2"></i>
                    <h2 class="mb-0">
                        @php
                            $totalRecords = $attendance->count();
                            $presentCount = $attendance->where('status', 'present')->count();
                            $rate = $totalRecords > 0 ? ($presentCount / $totalRecords) * 100 : 0;
                        @endphp
                        {{ number_format($rate, 1) }}%
                    </h2>
                    <small>Taux de Présence Global</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h2 class="mb-0">{{ $attendance->where('status', 'present')->count() }}</h2>
                    <small>Total Présents</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h2 class="mb-0">{{ $attendance->where('status', 'absent')->count() }}</h2>
                    <small>Total Absents</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Répartition Présent/Absent</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendancePieChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Tendance (30 derniers jours)</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceTrendChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres et Recherche</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('directeur.attendance.index') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-3">
                    <label class="form-label">Rechercher un étudiant</label>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Nom de l'étudiant..." value="{{ request('search') }}">
                </div>

                <!-- Class Filter -->
                <div class="col-md-2">
                    <label class="form-label">Classe</label>
                    <select class="form-select" name="class_id">
                        <option value="">Toutes</option>
                        @foreach(\App\Models\ClassRoom::all() as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject Filter -->
                <div class="col-md-2">
                    <label class="form-label">Matière</label>
                    <select class="form-select" name="subject_id">
                        <option value="">Toutes</option>
                        @foreach(\App\Models\Subject::all() as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status">
                        <option value="">Tous</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Présent</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="col-md-2">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                </div>

                <!-- Buttons -->
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Active Filters -->
            @if(request()->hasAny(['search', 'class_id', 'subject_id', 'status', 'date']))
                <div class="mt-3">
                    <strong>Filtres actifs:</strong>
                    @if(request('search'))
                        <span class="badge bg-info">Recherche: {{ request('search') }}</span>
                    @endif
                    @if(request('class_id'))
                        <span class="badge bg-primary">Classe: {{ \App\Models\ClassRoom::find(request('class_id'))->name }}</span>
                    @endif
                    @if(request('subject_id'))
                        <span class="badge bg-success">Matière: {{ \App\Models\Subject::find(request('subject_id'))->name }}</span>
                    @endif
                    @if(request('status'))
                        <span class="badge bg-warning">Statut: {{ ucfirst(request('status')) }}</span>
                    @endif
                    @if(request('date'))
                        <span class="badge bg-secondary">Date: {{ request('date') }}</span>
                    @endif
                    <a href="{{ route('directeur.attendance.index') }}" class="badge bg-danger text-decoration-none">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="mb-3 text-end">
        <button class="btn btn-success" onclick="alert('Export CSV sera implémenté prochainement')">
            <i class="fas fa-file-csv"></i> Exporter CSV
        </button>
        <button class="btn btn-danger" onclick="alert('Export PDF sera implémenté prochainement')">
            <i class="fas fa-file-pdf"></i> Exporter PDF
        </button>
    </div>

    <!-- Attendance Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="fas fa-table"></i> Liste des Présences
                <span class="badge bg-light text-dark ms-2">{{ $attendance->total() }} résultats</span>
            </h5>
        </div>
        <div class="card-body">
            @if($attendance->isNotEmpty())
                <p class="text-muted mb-3">Affichage de {{ $attendance->count() }} sur {{ $attendance->total() }} enregistrements</p>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Étudiant</th>
                                <th>Classe</th>
                                <th>Matière</th>
                                <th>Statut</th>
                                <th>Raison</th>
                                <th>Justification Parent</th>
                                <th>Marqué par</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendance as $record)
                                <tr class="{{ $record->status == 'absent' ? 'table-danger bg-opacity-10' : '' }}">
                                    <td>{{ $record->date->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('directeur.students.show', $record->student->id) }}" class="text-decoration-none">
                                            <strong>{{ $record->student->user->name }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        @if($record->student->classRoom)
                                            <span class="badge bg-primary">{{ $record->student->classRoom->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info">{{ $record->subject->name }}</span></td>
                                    <td>
                                        <span class="badge fs-6 bg-{{ $record->status == 'present' ? 'success' : 'danger' }}">
                                            <i class="fas fa-{{ $record->status == 'present' ? 'check' : 'times' }}"></i>
                                            {{ $record->status == 'present' ? 'Présent' : 'Absent' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($record->reason)
                                            <span title="{{ $record->reason }}">
                                                {{ Str::limit($record->reason, 20) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->parent_justification)
                                            <span class="text-success" title="{{ $record->parent_justification }}">
                                                <i class="fas fa-check-circle"></i> {{ Str::limit($record->parent_justification, 20) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->marked_by)
                                            {{ \App\Models\User::find($record->marked_by)->name ?? '-' }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" 
                                                onclick="alert('Date: {{ $record->date->format('d/m/Y') }}\nÉtudiant: {{ $record->student->user->name }}\nStatut: {{ $record->status }}\nRaison: {{ $record->reason ?? 'Aucune' }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $attendance->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucun enregistrement de présence trouvé</h4>
                    <p class="text-muted">Modifiez vos filtres ou ajoutez des présences via l'espace enseignant</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Analysis Section -->
    @if($attendance->isNotEmpty())
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Étudiants à Surveiller</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Étudiants avec un taux de présence inférieur à 80%</p>
                @php
                    $students = \App\Models\Student::all();
                    $lowAttendance = $students->filter(fn($s) => $s->attendanceRate() < 80 && $s->attendanceRate() > 0)->sortBy(fn($s) => $s->attendanceRate());
                @endphp
                @if($lowAttendance->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Classe</th>
                                    <th>Taux de Présence</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowAttendance->take(10) as $student)
                                    <tr>
                                        <td><strong>{{ $student->user->name }}</strong></td>
                                        <td>
                                            @if($student->classRoom)
                                                <span class="badge bg-primary">{{ $student->classRoom->name }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">{{ number_format($student->attendanceRate(), 1) }}%</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('directeur.students.show', $student->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-success">Tous les étudiants ont un taux de présence satisfaisant! 🎉</p>
                @endif
            </div>
        </div>
    @endif
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
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Attendance Trend Chart (placeholder - would need date-based data)
const trendCtx = document.getElementById('attendanceTrendChart').getContext('2d');
new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
        datasets: [{
            label: 'Taux de Présence (%)',
            data: [85, 88, 82, {{ number_format($rate, 1) }}],
            borderColor: 'rgb(16, 185, 129)',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
</script>
@endpush
@endsection
