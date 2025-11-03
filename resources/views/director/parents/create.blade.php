@extends('layouts.app')

@section('title', 'Ajouter un Parent')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('directeur.parents.index') }}">Parents</a></li>
            <li class="breadcrumb-item active">Ajouter</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-user-plus"></i> Ajouter un Parent</h1>
        <a href="{{ route('directeur.parents.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('directeur.parents.store') }}" method="POST" enctype="multipart/form-data">
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

                <!-- Contact Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-address-book"></i> Coordonnées</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="+221 77 123 45 67">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommandé pour les communications importantes</small>
                        </div>

                        <div class="mb-3">
                            <label for="relationship" class="form-label">Relation avec l'enfant</label>
                            <select class="form-select @error('relationship') is-invalid @enderror" 
                                    id="relationship" name="relationship">
                                <option value="">Sélectionner...</option>
                                <option value="Père" {{ old('relationship') == 'Père' ? 'selected' : '' }}>Père</option>
                                <option value="Mère" {{ old('relationship') == 'Mère' ? 'selected' : '' }}>Mère</option>
                                <option value="Tuteur" {{ old('relationship') == 'Tuteur' ? 'selected' : '' }}>Tuteur</option>
                                <option value="Tutrice" {{ old('relationship') == 'Tutrice' ? 'selected' : '' }}>Tutrice</option>
                            </select>
                            @error('relationship')
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

                <!-- Info Alert -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Note importante :</strong> 
                    Les enfants seront liés à ce parent lors de la création ou modification des étudiants.
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
                        <a href="{{ route('directeur.parents.index') }}" class="btn btn-secondary w-100">
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
