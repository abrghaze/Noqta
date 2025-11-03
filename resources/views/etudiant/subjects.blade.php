@extends('layouts.app')

@section('title', 'Mes Matières')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Mes Matières</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4"><i class="fas fa-book"></i> Mes Matières</h1>

    @if($subjects && $subjects->isNotEmpty())
        <div class="row">
            @foreach($subjects as $subject)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm h-100 hover-card" style="cursor: pointer;" 
                         onclick="window.location='{{ route('etudiant.subjects.show', $subject->id) }}'">
                        <div class="card-header text-white" 
                             style="background: linear-gradient(135deg, {{ '#' . substr(md5($subject->name), 0, 6) }} 0%, {{ '#' . substr(md5($subject->name . 'gradient'), 0, 6) }} 100%);">
                            <h5 class="mb-0">
                                <i class="fas fa-book-open"></i> {{ $subject->name }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Teacher Info -->
                            <div class="d-flex align-items-center mb-3">
                                @if($subject->teacher && $subject->teacher->user && $subject->teacher->user->profile_picture)
                                    <img src="{{ asset('storage/' . $subject->teacher->user->profile_picture) }}" 
                                         alt="{{ $subject->teacher->user->name }}" 
                                         class="rounded-circle me-3" 
                                         width="50" height="50">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $subject->teacher->user->name ?? 'Non assigné' }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope"></i> 
                                        @if($subject->teacher && $subject->teacher->user)
                                            <a href="mailto:{{ $subject->teacher->user->email }}">
                                                {{ $subject->teacher->user->email }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <hr>

                            <!-- Subject Details -->
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="p-2">
                                        <h4 class="mb-0 text-primary">{{ $subject->coefficient }}</h4>
                                        <small class="text-muted">Coefficient</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2">
                                        @php
                                            $studentGrades = $grades->where('subject_id', $subject->id);
                                            $subjectAverage = $studentGrades->isNotEmpty() ? $studentGrades->avg('grade_value') : 0;
                                        @endphp
                                        <h4 class="mb-0 text-{{ $subjectAverage >= 15 ? 'success' : ($subjectAverage >= 10 ? 'warning' : 'danger') }}">
                                            {{ number_format($subjectAverage, 2) }}
                                        </h4>
                                        <small class="text-muted">Ma Moyenne</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($subject->description)
                                <hr>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle"></i> {{ $subject->description }}
                                </p>
                            @endif
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-clipboard-list"></i> 
                                    {{ $grades->where('subject_id', $subject->id)->count() }} note(s)
                                </small>
                                <button class="btn btn-sm btn-primary">
                                    Voir détails <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucune matière assignée</h4>
                <p class="text-muted">Les matières apparaîtront ici une fois que vous serez inscrit dans une classe.</p>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endpush
@endsection
