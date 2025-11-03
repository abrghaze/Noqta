@extends('layouts.app')

@section('title', 'Gestion des Étudiants')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Étudiants</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-user-graduate"></i> Gestion des Étudiants</h1>
        <a href="{{ route('directeur.students.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter Étudiant
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('directeur.students.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Rechercher</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Nom, email ou matricule..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="class_id" class="form-label">Classe</label>
                    <select class="form-select" id="class_id" name="class_id">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="parent_id" class="form-label">Parent</label>
                    <select class="form-select" id="parent_id" name="parent_id">
                        <option value="">Tous les parents</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('directeur.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Students Table Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> Liste des Étudiants
                <span class="badge bg-light text-dark ms-2">{{ $students->total() }} total</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Matricule</th>
                            <th>Classe</th>
                            <th>Parent</th>
                            <th>Date d'inscription</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    @if($student->user->profile_picture)
                                        <img src="{{ asset('storage/' . $student->user->profile_picture) }}" 
                                             alt="{{ $student->user->name }}" 
                                             class="rounded-circle" 
                                             width="40" height="40">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $student->user->name }}</strong></td>
                                <td>{{ $student->user->email }}</td>
                                <td><span class="badge bg-info">{{ $student->matricule }}</span></td>
                                <td>
                                    @if($student->classRoom)
                                        <span class="badge bg-primary">{{ $student->classRoom->name }}</span>
                                    @else
                                        <span class="text-muted">Non assigné</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->parent)
                                        {{ $student->parent->user->name }}
                                    @else
                                        <span class="text-muted">Aucun</span>
                                    @endif
                                </td>
                                <td>{{ $student->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('directeur.students.show', $student->id) }}" 
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('directeur.students.edit', $student->id) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $student->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun étudiant trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet étudiant ?</p>
                <p class="text-danger"><strong>Attention :</strong> Toutes les notes et présences de cet étudiant seront également supprimées.</p>
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
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/directeur/students/${studentId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
@endsection
