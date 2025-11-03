@extends('layouts.app')

@section('title', 'Ajouter un Étudiant')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('directeur.students.index') }}">Étudiants</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-user-plus"></i> Ajouter un Étudiant</h1>
        <a href="{{ route('directeur.students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('directeur.students.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- User Information Card -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Informations de l'utilisateur</h5>
                    </div>
                    <div class="card-body">
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required 
                                       minlength="8">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 8 caractères</small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer mot de passe <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required 
                                       minlength="8">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Informations de l'étudiant</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Matricule -->
                            <div class="col-md-6 mb-3">
                                <label for="matricule" class="form-label">Numéro d'étudiant <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('matricule') is-invalid @enderror" 
                                       id="matricule" 
                                       name="matricule" 
                                       value="{{ old('matricule', 'STU' . date('Y') . rand(1000, 9999)) }}" 
                                       required>
                                @error('matricule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth') }}" 
                                       required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Class -->
                            <div class="col-md-6 mb-3">
                                <label for="class_id" class="form-label">Classe <span class="text-danger">*</span></label>
                                <select class="form-select @error('class_id') is-invalid @enderror" 
                                        id="class_id" 
                                        name="class_id" 
                                        required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Parent -->
                            <div class="col-md-6 mb-3">
                                <label for="parent_id" class="form-label">Parent</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" 
                                        id="parent_id" 
                                        name="parent_id">
                                    <option value="">Aucun parent</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="+221 77 123 45 67">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Picture Card -->
            <div class="col-lg-4">
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
                            <input type="file" 
                                   class="form-control @error('profile_picture') is-invalid @enderror" 
                                   id="profile_picture" 
                                   name="profile_picture" 
                                   accept="image/*" 
                                   onchange="previewImage(event)">
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
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <a href="{{ route('directeur.students.index') }}" class="btn btn-secondary w-100">
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
        const preview = document.getElementById('profilePreview');
        preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endpush
@endsection
