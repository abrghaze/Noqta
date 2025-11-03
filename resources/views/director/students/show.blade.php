@extends('layouts.app')

@section('title', 'Profil de ' . $student->user->name)

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('directeur.students.index') }}">Étudiants</a></li>
            <li class="breadcrumb-item active">{{ $student->user->name }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-user"></i> Profil de {{ $student->user->name }}</h1>
        <div>
            <a href="{{ route('directeur.students.edit', $student->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <button class="btn btn-danger" onclick="confirmDelete({{ $student->id }})">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Student Info Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    @if($student->user->profile_picture)
                        <img src="{{ asset('storage/' . $student->user->profile_picture) }}" 
                             alt="{{ $student->user->name }}" 
                             class="img-fluid rounded-circle mb-3" 
                             style="max-width: 200px;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 200px; height: 200px; font-size: 5rem;">
                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <h3>{{ $student->user->name }}</h3>
                    <p class="text-muted">{{ $student->user->email }}</p>
                    <span class="badge bg-info mb-3">{{ $student->matricule }}</span>
                    
                    <hr>
                    
                    <div class="text-start">
                        <p><strong><i class="fas fa-door-open"></i> Classe:</strong> 
                            @if($student->classRoom)
                                <a href="{{ route('directeur.classes.show', $student->classRoom->id) }}">
                                    {{ $student->classRoom->name }}
                                </a>
                            @else
                                <span class="text-muted">Non assigné</span>
                            @endif
                        </p>
                        <p><strong><i class="fas fa-user-friends"></i> Parent:</strong> 
                            @if($student->parent)
                                <a href="{{ route('directeur.parents.show', $student->parent->id) }}">
                                    {{ $student->parent->user->name }}
                                </a>
                            @else
                                <span class="text-muted">Aucun</span>
                            @endif
                        </p>
                        <p><strong><i class="fas fa-birthday-cake"></i> Date de naissance:</strong> 
                            {{ $student->date_of_birth ? $student->date_of_birth->format('d/m/Y') : 'N/A' }}
                        </p>
                        <p><strong><i class="fas fa-phone"></i> Téléphone:</strong> 
                            {{ $student->phone ?? 'N/A' }}
                        </p>
                        <p><strong><i class="fas fa-calendar"></i> Inscrit le:</strong> 
                            {{ $student->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics and Details -->
        <div class="col-lg-8">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="mb-0">{{ number_format($stats['average_grade'], 2) }}</h2>
                            <small>Moyenne Générale</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="mb-0">{{ number_format($stats['attendance_rate'], 1) }}%</h2>
                            <small>Taux de Présence</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="mb-0">{{ $stats['total_grades'] }}</h2>
                            <small>Notes Reçues</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning shadow-sm">
                        <div class="card-body text-center">
                            <h2 class="mb-0">{{ $stats['total_absences'] }}</h2>
                            <small>Absences</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Grades -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Notes Récentes</h5>
                    <a href="#" class="btn btn-sm btn-light">Voir tout</a>
                </div>
                <div class="card-body">
                    @if($student->grades->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Matière</th>
                                        <th>Note</th>
                                        <th>Type</th>
                                        <th>Enseignant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->grades->take(10) as $grade)
                                        <tr>
                                            <td>{{ $grade->date->format('d/m/Y') }}</td>
                                            <td>{{ $grade->subject->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $grade->grade_value >= 15 ? 'success' : ($grade->grade_value >= 10 ? 'warning' : 'danger') }}">
                                                    {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                                </span>
                                            </td>
                                            <td><span class="badge bg-primary">{{ $grade->exam_type }}</span></td>
                                            <td>{{ $grade->teacher->user->name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Aucune note enregistrée</p>
                    @endif
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Présences Récentes</h5>
                    <a href="#" class="btn btn-sm btn-light">Voir tout</a>
                </div>
                <div class="card-body">
                    @if($student->attendance->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Matière</th>
                                        <th>Statut</th>
                                        <th>Raison</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->attendance->take(10) as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                            <td>{{ $attendance->subject->name }}</td>
                                            <td>
                                                <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : 'danger' }}">
                                                    {{ $attendance->status == 'present' ? 'Présent' : 'Absent' }}
                                                </span>
                                            </td>
                                            <td>{{ $attendance->reason ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Aucune présence enregistrée</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet étudiant ?</p>
                <p class="text-danger"><strong>Attention :</strong> Toutes les notes et présences seront également supprimées.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(studentId) {
    document.getElementById('deleteForm').action = `/directeur/students/${studentId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
