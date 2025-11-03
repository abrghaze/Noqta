@extends('layouts.app')

@section('title', 'Notes de ' . ($child->user->name ?? 'mon enfant'))

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Notes de {{ $child->user->name }}</li>
        </ol>
    </nav>

    <!-- Child Selector (if multiple children) -->
    @if(isset($children) && $children->count() > 1)
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <label class="form-label"><i class="fas fa-child"></i> Sélectionner un enfant:</label>
                <select class="form-select" onchange="window.location.href='/parent/children/' + this.value + '/grades'">
                    @foreach($children as $c)
                        <option value="{{ $c->id }}" {{ $child->id == $c->id ? 'selected' : '' }}>
                            {{ $c->user->name }} - {{ $c->classRoom->name ?? 'Non assigné' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-clipboard-list"></i> Notes de {{ $child->user->name }}</h1>
        <button class="btn btn-primary">
            <i class="fas fa-download"></i> Télécharger le bulletin
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ number_format($average, 2) }}</h2>
                    <p class="mb-0">Moyenne Générale</p>
                    <small>/20</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $grades->count() }}</h2>
                    <p class="mb-0">Total Notes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $grades->max('grade_value') ?? 0 }}</h2>
                    <p class="mb-0">Note la Plus Haute</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-4 mb-0">{{ $grades->min('grade_value') ?? 0 }}</h2>
                    <p class="mb-0">Note la Plus Basse</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Toutes les notes</h5>
        </div>
        <div class="card-body">
            @if($grades->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Matière</th>
                                <th>Note</th>
                                <th>Type</th>
                                <th>Enseignant</th>
                                <th>Commentaires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $grade)
                                <tr>
                                    <td>{{ $grade->date->format('d/m/Y') }}</td>
                                    <td><strong>{{ $grade->subject->name }}</strong></td>
                                    <td>
                                        <span class="badge fs-6 bg-{{ $grade->grade_value >= 15 ? 'success' : ($grade->grade_value >= 10 ? 'warning' : 'danger') }}">
                                            {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-primary">{{ $grade->exam_type }}</span></td>
                                    <td>{{ $grade->teacher->user->name ?? 'N/A' }}</td>
                                    <td>{{ $grade->comment ?? '-' }}</td>
                                    <td>
                                        @if($grade->teacher && $grade->teacher->user)
                                            <a href="mailto:{{ $grade->teacher->user->email }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Contacter l'enseignant">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Aucune note enregistrée</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
