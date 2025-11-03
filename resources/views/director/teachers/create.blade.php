@extends('layouts.app')

@section('title', 'Ajouter un Enseignant')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('directeur.teachers.index') }}">Enseignants</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-user-plus"></i> Ajouter un Enseignant</h1>
        <a href="{{ route('directeur.teachers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('directeur.teachers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Personal Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Informations Personnelles</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required minlength="8">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 8 caractères</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required minlength="8">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Informations Professionnelles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="specialization" class="form-label">Spécialisation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                       id="specialization" name="specialization" 
                                       value="{{ old('specialization') }}" 
                                       placeholder="Ex: Mathématiques, Physique..." required>
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="hire_date" class="form-label">Date d'embauche</label>
                                <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                       id="hire_date" name="hire_date" 
                                       value="{{ old('hire_date', date('Y-m-d')) }}">
                                @error('hire_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="+221 77 123 45 67">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Profile Picture Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-camera"></i> Photo de profil</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img id="profilePreview" 
                                 src="https://via.placeholder.com/200x200?text=Photo" 
                                 alt="Profile Preview" 
                                 class="img-fluid rounded-circle mb-3" 
                                 style="max-width: 200px;">
                        </div>
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Choisir une photo</label>
                            <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" 
                                   id="profile_picture" name="profile_picture" 
                                   accept="image/*" onchange="previewImage(event)">
                            @error('profile_picture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">JPG, PNG ou GIF (Max 2MB)</small>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons Card -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="{{ route('directeur.teachers.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('profilePreview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endpush
@endsection
