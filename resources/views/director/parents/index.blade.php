@extends('layouts.app')

@section('title', 'Gestion des Parents')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Parents</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-user-friends"></i> Gestion des Parents</h1>
        <a href="{{ route('directeur.parents.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter Parent
        </a>
    </div>

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

    <!-- Search Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('directeur.parents.index') }}" class="row g-3">
                <div class="col-md-10">
                    <label for="search" class="form-label">Rechercher</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Rechercher par nom, email ou téléphone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('directeur.parents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Parents Table Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-users"></i> Liste des Parents
                <span class="badge bg-light text-dark ms-2">{{ $parents->total() }} total</span>
            </h5>
        </div>
        <div class="card-body">
            @if($parents->isNotEmpty())
                <p class="text-muted mb-3">
                    Affichage de {{ $parents->count() }} sur {{ $parents->total() }} parents
                </p>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Enfants</th>
                                <th>Date d'inscription</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parents as $parent)
                                <tr>
                                    <td>
                                        @if($parent->user->profile_picture)
                                            <img src="{{ asset('storage/' . $parent->user->profile_picture) }}" 
                                                 alt="{{ $parent->user->name }}" 
                                                 class="rounded-circle" 
                                                 width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($parent->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $parent->user->name }}</strong></td>
                                    <td>{{ $parent->user->email }}</td>
                                    <td>{{ $parent->phone ?? '-' }}</td>
                                    <td>
                                        @php
                                            $childrenCount = $parent->children ? $parent->children->count() : 0;
                                        @endphp
                                        @if($childrenCount > 0)
                                            <span class="badge bg-success">{{ $childrenCount }} enfant(s)</span><br>
                                            <small class="text-muted">
                                                @foreach($parent->children->take(2) as $child)
                                                    {{ $child->user->name }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                                @if($childrenCount > 2)
                                                    <span class="text-primary">+{{ $childrenCount - 2 }}</span>
                                                @endif
                                            </small>
                                        @else
                                            <span class="text-muted">Aucun enfant</span>
                                        @endif
                                    </td>
                                    <td>{{ $parent->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('directeur.parents.show', $parent->id) }}" 
                                               class="btn btn-sm btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('directeur.parents.edit', $parent->id) }}" 
                                               class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete({{ $parent->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $parents->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-friends fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">Aucun parent trouvé</h4>
                    <p class="text-muted mb-3">Commencez par ajouter votre premier parent</p>
                    <a href="{{ route('directeur.parents.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter le premier parent
                    </a>
                </div>
            @endif
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
                <p>Êtes-vous sûr de vouloir supprimer ce parent ?</p>
                <p class="text-warning"><strong>Note :</strong> Les enfants liés seront déliés mais ne seront pas supprimés.</p>
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
function confirmDelete(parentId) {
    document.getElementById('deleteForm').action = `/directeur/parents/${parentId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
@endsection
