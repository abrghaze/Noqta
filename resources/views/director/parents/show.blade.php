@extends('layouts.app')

@section('title', 'Profil de ' . $parent->user->name)

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('directeur.parents.index') }}">Parents</a></li>
            <li class="breadcrumb-item active">{{ $parent->user->name }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-user-friends"></i> Profil de {{ $parent->user->name }}</h1>
        <div>
            <a href="{{ route('directeur.parents.edit', $parent->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <button class="btn btn-danger" onclick="confirmDelete({{ $parent->id }})">
                <i class="fas fa-trash"></i> Supprimer
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Parent Info Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    @if($parent->user->profile_picture)
                        <img src="{{ asset('storage/' . $parent->user->profile_picture) }}" 
                             alt="{{ $parent->user->name }}" 
                             class="img-fluid rounded-circle mb-3" 
                             style="max-width: 150px;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 150px; height: 150px; font-size: 4rem;">
                            {{ strtoupper(substr($parent->user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <h3>{{ $parent->user->name }}</h3>
                    <span class="badge bg-primary mb-3">Parent</span>
                    
                    <hr>
                    
                    <div class="text-start">
                        <p><strong><i class="fas fa-envelope"></i> Email:</strong><br>
                            <a href="mailto:{{ $parent->user->email }}">{{ $parent->user->email }}</a>
                        </p>
                        @if($parent->phone)
                            <p><strong><i class="fas fa-phone"></i> Téléphone:</strong><br>
                                <a href="tel:{{ $parent->phone }}">{{ $parent->phone }}</a>
                            </p>
                        @endif
                        @if($parent->relationship)
                            <p><strong><i class="fas fa-users"></i> Relation:</strong><br>
                                {{ $parent->relationship }}
                            </p>
                        @endif
                        @if($parent->address)
                            <p><strong><i class="fas fa-map-marker-alt"></i> Adresse:</strong><br>
                                {{ $parent->address }}
                            </p>
                        @endif
                        <p><strong><i class="fas fa-calendar"></i> Inscrit le:</strong><br>
                            {{ $parent->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            @if($parent->children && $parent->children->isNotEmpty())
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h2 class="text-primary mb-0">{{ $parent->children->count() }}</h2>
                            <p class="text-muted mb-0">Enfant(s)</p>
                        </div>
                        @php
                            $averages = $parent->children->map(fn($child) => $child->averageGrade())->filter();
                            $overallAverage = $averages->isNotEmpty() ? $averages->avg() : 0;
                        @endphp
                        <div class="text-center">
                            <h2 class="text-success mb-0">{{ number_format($overallAverage, 2) }}/20</h2>
                            <p class="text-muted mb-0">Moyenne des enfants</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Children and Activity -->
        <div class="col-lg-8">
            <!-- Children Cards -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-child"></i> Enfants</h5>
                </div>
                <div class="card-body">
                    @if($parent->children && $parent->children->isNotEmpty())
                        <div class="row">
                            @foreach($parent->children as $child)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                @if($child->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $child->user->profile_picture) }}" 
                                                         alt="{{ $child->user->name }}" 
                                                         class="rounded-circle me-3" 
                                                         width="60" height="60">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 60px; height: 60px; font-size: 1.5rem;">
                                                        {{ strtoupper(substr($child->user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h5 class="mb-1">{{ $child->user->name }}</h5>
                                                    @if($child->classRoom)
                                                        <span class="badge bg-primary">{{ $child->classRoom->name }}</span>
                                                    @else
                                                        <span class="text-muted">Non assigné</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <h4 class="mb-0 text-{{ $child->averageGrade() >= 15 ? 'success' : ($child->averageGrade() >= 10 ? 'warning' : 'danger') }}">
                                                        {{ number_format($child->averageGrade(), 2) }}
                                                    </h4>
                                                    <small class="text-muted">Moyenne</small>
                                                </div>
                                                <div class="col-6">
                                                    <h4 class="mb-0 text-{{ $child->attendanceRate() >= 80 ? 'success' : 'warning' }}">
                                                        {{ number_format($child->attendanceRate(), 1) }}%
                                                    </h4>
                                                    <small class="text-muted">Présence</small>
                                                </div>
                                            </div>
                                            
                                            <hr>
                                            
                                            <a href="{{ route('directeur.students.show', $child->id) }}" class="btn btn-sm btn-primary w-100">
                                                <i class="fas fa-eye"></i> Voir le profil complet
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-child fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">Aucun enfant lié à ce parent</p>
                            <p class="text-muted">Liez un enfant lors de la création ou modification d'un étudiant</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            @if($parent->children && $parent->children->isNotEmpty())
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Activité Récente des Enfants</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $recentGrades = collect();
                            $recentAbsences = collect();
                            foreach($parent->children as $child) {
                                $recentGrades = $recentGrades->merge($child->grades()->latest()->take(5)->get());
                                $recentAbsences = $recentAbsences->merge($child->attendance()->where('status', 'absent')->latest()->take(5)->get());
                            }
                            $recentGrades = $recentGrades->sortByDesc('created_at')->take(5);
                            $recentAbsences = $recentAbsences->sortByDesc('created_at')->take(5);
                        @endphp
                        
                        @if($recentGrades->isNotEmpty() || $recentAbsences->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Enfant</th>
                                            <th>Type</th>
                                            <th>Détails</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentGrades as $grade)
                                            <tr>
                                                <td>{{ $grade->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $grade->student->user->name }}</td>
                                                <td><span class="badge bg-primary">Note</span></td>
                                                <td>{{ $grade->subject->name }}: {{ $grade->grade_value }}/{{ $grade->max_grade }}</td>
                                            </tr>
                                        @endforeach
                                        @foreach($recentAbsences as $absence)
                                            <tr>
                                                <td>{{ $absence->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $absence->student->user->name }}</td>
                                                <td><span class="badge bg-danger">Absence</span></td>
                                                <td>{{ $absence->subject->name }} - {{ $absence->reason ?? 'Non justifié' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted py-3">Aucune activité récente</p>
                        @endif
                    </div>
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
                <p class="text-warning"><strong>Note :</strong> Les enfants seront déliés mais ne seront pas supprimés.</p>
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
