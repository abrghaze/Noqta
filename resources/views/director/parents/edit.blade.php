@extends('layouts.app')

@section('title', 'Modifier le Parent')

@section('content')
<div class="container-fluid px-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('directeur.parents.index') }}">Parents</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-edit"></i> Modifier le Parent: {{ $parent->user->name }}</h1>
        <a href="{{ route('directeur.parents.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <form action="{{ route('directeur.parents.update', $parent->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Informations Personnelles</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $parent->user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $parent->user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Laissez les champs de mot de passe vides si vous ne souhaitez pas le modifier.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" minlength="8">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmer mot de passe</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" minlength="8">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-address-book"></i> Coordonnées</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $parent->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="relationship" class="form-label">Relation avec l'enfant</label>
                            <select class="form-select @error('relationship') is-invalid @enderror" 
                                    id="relationship" name="relationship">
                                <option value="">Sélectionner...</option>
                                <option value="Père" {{ old('relationship', $parent->relationship) == 'Père' ? 'selected' : '' }}>Père</option>
                                <option value="Mère" {{ old('relationship', $parent->relationship) == 'Mère' ? 'selected' : '' }}>Mère</option>
                                <option value="Tuteur" {{ old('relationship', $parent->relationship) == 'Tuteur' ? 'selected' : '' }}>Tuteur</option>
                                <option value="Tutrice" {{ old('relationship', $parent->relationship) == 'Tutrice' ? 'selected' : '' }}>Tutrice</option>
                            </select>
                            @error('relationship')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $parent->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Linked Children (Read-only) -->
                @if($parent->children && $parent->children->isNotEmpty())
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-child"></i> Enfants Liés</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                <i class="fas fa-info-circle"></i> Ces liens sont gérés via la gestion des étudiants
                            </p>
                            <div class="row">
                                @foreach($parent->children as $child)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body d-flex align-items-center">
                                                @if($child->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $child->user->profile_picture) }}" 
                                                         alt="{{ $child->user->name }}" 
                                                         class="rounded-circle me-3" 
                                                         width="50" height="50">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 50px; height: 50px;">
                                                        {{ strtoupper(substr($child->user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $child->user->name }}</strong><br>
                                                    <small class="text-muted">
                                                        @if($child->classRoom)
                                                            <span class="badge bg-primary">{{ $child->classRoom->name }}</span>
                                                        @else
                                                            Non assigné
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-camera"></i> Photo de profil</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img id="profilePreview" 
                                 src="{{ $parent->user->profile_picture ? asset('storage/' . $parent->user->profile_picture) : 'https://via.placeholder.com/200x200?text=Photo' }}" 
                                 alt="Profile Preview" 
                                 class="img-fluid rounded-circle mb-3" 
                                 style="max-width: 200px;">
                        </div>
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Changer la photo</label>
                            <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" 
                                   id="profile_picture" name="profile_picture" 
                                   accept="image/*" onchange="previewImage(event)">
                            @error('profile_picture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save"></i> Mettre à jour
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
