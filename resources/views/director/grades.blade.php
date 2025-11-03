@extends('layouts.app')

@section('title', 'Toutes les Notes du Système')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Toutes les Notes</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4"><i class="fas fa-clipboard-list"></i> Toutes les Notes du Système</h1>

    <!-- Statistics Dashboard -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                    <h2 class="mb-0">{{ $grades->total() }}</h2>
                    <small>Total Notes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h2 class="mb-0">{{ number_format($grades->avg('grade_value'), 2) }}</h2>
                    <small>Moyenne Générale</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-arrow-up fa-2x mb-2"></i>
                    <h2 class="mb-0">{{ $grades->max('grade_value') ?? 0 }}</h2>
                    <small>Note la Plus Haute</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-arrow-down fa-2x mb-2"></i>
                    <h2 class="mb-0">{{ $grades->min('grade_value') ?? 0 }}</h2>
                    <small>Note la Plus Basse</small>
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
            <form method="GET" action="{{ route('directeur.grades.index') }}" class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
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

                <!-- Type Filter -->
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select class="form-select" name="type">
                        <option value="">Tous</option>
                        <option value="Composition" {{ request('type') == 'Composition' ? 'selected' : '' }}>Composition</option>
                        <option value="Devoir" {{ request('type') == 'Devoir' ? 'selected' : '' }}>Devoir</option>
                        <option value="Contrôle" {{ request('type') == 'Contrôle' ? 'selected' : '' }}>Contrôle</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('directeur.grades.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'class_id', 'subject_id', 'type']))
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
                    @if(request('type'))
                        <span class="badge bg-warning">Type: {{ request('type') }}</span>
                    @endif
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

    <!-- Grades Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="fas fa-table"></i> Liste des Notes
                <span class="badge bg-light text-dark ms-2">{{ $grades->total() }} résultats</span>
            </h5>
        </div>
        <div class="card-body">
            @if($grades->isNotEmpty())
                <p class="text-muted mb-3">Affichage de {{ $grades->count() }} sur {{ $grades->total() }} notes</p>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Étudiant</th>
                                <th>Classe</th>
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
                                    <td>
                                        <a href="{{ route('directeur.students.show', $grade->student->id) }}" class="text-decoration-none">
                                            <strong>{{ $grade->student->user->name }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        @if($grade->student->classRoom)
                                            <span class="badge bg-primary">{{ $grade->student->classRoom->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info">{{ $grade->subject->name }}</span></td>
                                    <td>
                                        <span class="badge fs-6 bg-{{ $grade->grade_value >= 15 ? 'success' : ($grade->grade_value >= 10 ? 'warning' : 'danger') }}">
                                            {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $grade->exam_type }}</span></td>
                                    <td>
                                        @if($grade->teacher)
                                            <a href="{{ route('directeur.teachers.show', $grade->teacher->id) }}" class="text-decoration-none">
                                                {{ $grade->teacher->user->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($grade->comment)
                                            <span title="{{ $grade->comment }}">
                                                {{ Str::limit($grade->comment, 30) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="alert('Détails: {{ $grade->comment }}')">
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
                    {{ $grades->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucune note trouvée</h4>
                    <p class="text-muted">Modifiez vos filtres ou ajoutez des notes via l'espace enseignant</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
