@extends('layouts.app')

@section('title', 'Profil de ' . $teacher->user->name)

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('directeur.teachers.index') }}">Enseignants</a></li>
            <li class="breadcrumb-item active">{{ $teacher->user->name }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-chalkboard-teacher"></i> Profil de {{ $teacher->user->name }}</h1>
        <div>
            <a href="{{ route('directeur.teachers.edit', $teacher->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <button class="btn btn-danger" onclick="confirmDelete({{ $teacher->id }})">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Teacher Info Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    @if($teacher->user->profile_picture)
                        <img src="{{ asset('storage/' . $teacher->user->profile_picture) }}" 
                             alt="{{ $teacher->user->name }}" 
                             class="img-fluid rounded-circle mb-3" 
                             style="max-width: 150px;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 150px; height: 150px; font-size: 4rem;">
                            {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <h3>{{ $teacher->user->name }}</h3>
                    <span class="badge bg-primary mb-3">Enseignant</span>
                    
                    <hr>
                    
                    <div class="text-start">
                        <p><strong><i class="fas fa-envelope"></i> Email:</strong><br>
                            <a href="mailto:{{ $teacher->user->email }}">{{ $teacher->user->email }}</a>
                        </p>
                        @if($teacher->phone)
                            <p><strong><i class="fas fa-phone"></i> Téléphone:</strong><br>
                                <a href="tel:{{ $teacher->phone }}">{{ $teacher->phone }}</a>
                            </p>
                        @endif
                        <p><strong><i class="fas fa-graduation-cap"></i> Spécialisation:</strong><br>
                            {{ $teacher->specialization }}
                        </p>
                        <p><strong><i class="fas fa-calendar"></i> Inscrit le:</strong><br>
                            {{ $teacher->created_at->format('d/m/Y') }}
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
                            <i class="fas fa-chalkboard fa-2x mb-2"></i>
                            <h2 class="mb-0">{{ $stats['total_classes'] }}</h2>
                            <small>Classes</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h2 class="mb-0">{{ $stats['total_students'] }}</h2>
                            <small>Étudiants</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <h2 class="mb-0">{{ $stats['total_subjects'] }}</h2>
                            <small>Matières</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                            <h2 class="mb-0">0</h2>
                            <small>Notes ce mois</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Classes -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-door-open"></i> Classes Assignées</h5>
                </div>
                <div class="card-body">
                    @if($teacher->classes && $teacher->classes->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nom de la classe</th>
                                        <th>Nombre d'étudiants</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->classes as $class)
                                        <tr>
                                            <td><strong>{{ $class->name }}</strong></td>
                                            <td><span class="badge bg-info">{{ $class->students->count() }} étudiants</span></td>
                                            <td>
                                                <a href="{{ route('directeur.classes.show', $class->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-3">Aucune classe assignée</p>
                    @endif
                </div>
            </div>

            <!-- Assigned Subjects -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-book-open"></i> Matières Enseignées</h5>
                </div>
                <div class="card-body">
                    @if($teacher->subjects && $teacher->subjects->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Matière</th>
                                        <th>Classe</th>
                                        <th>Coefficient</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->subjects as $subject)
                                        <tr>
                                            <td><strong>{{ $subject->name }}</strong></td>
                                            <td>
                                                @if($subject->classRoom)
                                                    <span class="badge bg-primary">{{ $subject->classRoom->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $subject->coefficient }}</td>
                                            <td>
                                                <a href="{{ route('directeur.subjects.show', $subject->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-3">Aucune matière assignée</p>
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
                <p>Êtes-vous sûr de vouloir supprimer cet enseignant ?</p>
                <p class="text-warning"><strong>Note :</strong> Si cet enseignant a des matières assignées, la suppression sera refusée.</p>
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
function confirmDelete(teacherId) {
    document.getElementById('deleteForm').action = `/directeur/teachers/${teacherId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
