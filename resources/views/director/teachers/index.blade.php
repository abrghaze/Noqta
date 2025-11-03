@extends('layouts.app')

@section('title', 'Gestion des Enseignants')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Enseignants</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-chalkboard-teacher"></i> Gestion des Enseignants</h1>
        <a href="{{ route('directeur.teachers.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter Enseignant
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('directeur.teachers.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Rechercher</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Rechercher par nom ou email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="specialization" class="form-label">Filtrer par spécialisation</label>
                    <input type="text" class="form-control" id="specialization" name="specialization" 
                           placeholder="Ex: Mathématiques..." value="{{ request('specialization') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('directeur.teachers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Teachers Table Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> Liste des Enseignants
                <span class="badge bg-light text-dark ms-2">{{ $teachers->total() }} total</span>
            </h5>
        </div>
        <div class="card-body">
            @if($teachers->isNotEmpty())
                <p class="text-muted mb-3">
                    Affichage de {{ $teachers->count() }} sur {{ $teachers->total() }} enseignants
                </p>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Spécialisation</th>
                                <th>Matières</th>
                                <th>Classes</th>
                                <th>Date d'embauche</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                                <tr>
                                    <td>
                                        @if($teacher->user->profile_picture)
                                            <img src="{{ asset('storage/' . $teacher->user->profile_picture) }}" 
                                                 alt="{{ $teacher->user->name }}" 
                                                 class="rounded-circle" 
                                                 width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $teacher->user->name }}</strong></td>
                                    <td>{{ $teacher->user->email }}</td>
                                    <td>
                                        @if($teacher->specialization)
                                            <span class="badge bg-info">{{ $teacher->specialization }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($teacher->subjects && $teacher->subjects->count() > 0)
                                            @foreach($teacher->subjects->take(2) as $subject)
                                                <span class="badge bg-primary">{{ $subject->name }}</span>
                                            @endforeach
                                            @if($teacher->subjects->count() > 2)
                                                <span class="badge bg-secondary">+{{ $teacher->subjects->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Aucune</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $teacher->classes ? $teacher->classes->count() : 0 }} classe(s)
                                        </span>
                                    </td>
                                    <td>{{ $teacher->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('directeur.teachers.show', $teacher->id) }}" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('directeur.teachers.edit', $teacher->id) }}" 
                                               class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete({{ $teacher->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $teachers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">Aucun enseignant trouvé</h4>
                    <p class="text-muted mb-3">Commencez par ajouter votre premier enseignant</p>
                    <a href="{{ route('directeur.teachers.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter le premier enseignant
                    </a>
                </div>
            @endif
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
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/directeur/teachers/${teacherId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
@endsection
