@extends('layouts.app')

@section('title', 'Enseignants')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item active">Enseignants</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4"><i class="fas fa-chalkboard-teacher"></i> Enseignants de {{ $children->count() > 1 ? 'mes enfants' : 'mon enfant' }}</h1>

    <!-- Children Info -->
    @if($children->count() > 1)
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle"></i> Vous pouvez contacter les enseignants de vos enfants: 
            <strong>{{ $children->pluck('user.name')->implode(', ') }}</strong>
        </div>
    @endif

    @if($teachers && $teachers->isNotEmpty())
        <div class="row">
            @foreach($teachers as $teacher)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <!-- Teacher Photo -->
                            <div class="mb-3">
                                @if($teacher->user->profile_picture)
                                    <img src="{{ asset('storage/' . $teacher->user->profile_picture) }}" 
                                         alt="{{ $teacher->user->name }}" 
                                         class="rounded-circle" 
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                         style="width: 120px; height: 120px; font-size: 3rem;">
                                        {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Teacher Info -->
                            <h5 class="card-title mb-1">{{ $teacher->user->name }}</h5>
                            <p class="text-muted mb-3">
                                <i class="fas fa-graduation-cap"></i> {{ $teacher->specialization ?? 'Enseignant' }}
                            </p>

                            <hr>

                            <!-- Subjects -->
                            <div class="mb-3">
                                <h6 class="text-muted">Matières enseignées:</h6>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    @foreach($teacher->subjects as $subject)
                                        <span class="badge bg-primary">{{ $subject->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <hr>

                            <!-- Contact Info -->
                            <div class="mb-3">
                                <p class="mb-1">
                                    <i class="fas fa-envelope text-primary"></i>
                                    <a href="mailto:{{ $teacher->user->email }}">{{ $teacher->user->email }}</a>
                                </p>
                                @if($teacher->phone)
                                    <p class="mb-0">
                                        <i class="fas fa-phone text-success"></i>
                                        <a href="tel:{{ $teacher->phone }}">{{ $teacher->phone }}</a>
                                    </p>
                                @endif
                            </div>

                            <!-- Contact Button -->
                            <button class="btn btn-primary btn-sm w-100" 
                                    onclick="openContactModal('{{ $teacher->user->name }}', '{{ $teacher->user->email }}', {{ $teacher->id }})">
                                <i class="fas fa-comment-dots"></i> Contacter
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun enseignant trouvé</h4>
                <p class="text-muted">Les enseignants apparaîtront ici une fois que vos enfants seront inscrits dans des classes.</p>
            </div>
        </div>
    @endif
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-envelope"></i> Contacter <span id="teacherName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" id="contactForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">À:</label>
                        <input type="email" class="form-control" id="teacherEmail" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sujet:</label>
                        <select class="form-select" name="subject" required>
                            <option value="">Sélectionner un sujet</option>
                            <option value="general">Question générale</option>
                            <option value="grades">À propos des notes</option>
                            <option value="attendance">À propos des présences</option>
                            <option value="behavior">Comportement de l'enfant</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message:</label>
                        <textarea class="form-control" name="message" rows="5" 
                                  placeholder="Votre message..." required></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Ce message sera envoyé par email à l'enseignant.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openContactModal(name, email, teacherId) {
    document.getElementById('teacherName').textContent = name;
    document.getElementById('teacherEmail').value = email;
    document.getElementById('contactForm').action = `/parent/teachers/${teacherId}/contact`;
    new bootstrap.Modal(document.getElementById('contactModal')).show();
}
</script>
@endpush
@endsection
