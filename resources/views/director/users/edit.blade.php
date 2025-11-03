@extends('layouts.modern')

@section('title', 'Modifier l\'Utilisateur')
@section('breadcrumb', 'Modifier l\'Utilisateur')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier l'Utilisateur</h1>
                <p class="mt-2 text-sm text-gray-600">Mettre à jour les informations de {{ $user->name }}</p>
            </div>
            <a href="{{ route('directeur.users.index') }}" 
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl bg-white shadow-sm">
        <form method="POST" action="{{ route('directeur.users.update', $user) }}" id="editUserForm">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-2 text-indigo-600"></i>
                        Rôle <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Sélectionner un rôle</option>
                        <option value="etudiant" {{ old('role', $user->role) == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                        <option value="enseignant" {{ old('role', $user->role) == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                        <option value="parent" {{ old('role', $user->role) == 'parent' ? 'selected' : '' }}>Parent</option>
                        <option value="directeur" {{ old('role', $user->role) == 'directeur' ? 'selected' : '' }}>Directeur</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-indigo-600"></i>
                            Nom Complet <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ex: Ahmed Benali">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-indigo-600"></i>
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ex: ahmed.benali@school.ma">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-indigo-600"></i>
                            Nouveau mot de passe (optionnel)
                        </label>
                        <input type="password" name="password"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Laisser vide pour ne pas changer">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Laisser vide pour conserver le mot de passe actuel</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-indigo-600"></i>
                            Confirmer le nouveau mot de passe
                        </label>
                        <input type="password" name="password_confirmation"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Confirmer le mot de passe">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-indigo-600"></i>
                            Téléphone
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ex: 0612345678">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Student-specific fields -->
                <div id="studentFields" class="hidden space-y-6">
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Étudiant</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Matricule -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card mr-2 text-indigo-600"></i>
                                    Matricule <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="matricule" value="{{ old('matricule', $user->student->matricule ?? '') }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="Ex: STU2025001">
                                @error('matricule')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Class -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-door-open mr-2 text-indigo-600"></i>
                                    Classe <span class="text-red-500">*</span>
                                </label>
                                <select name="class_id"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Sélectionner une classe</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $user->student->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar mr-2 text-indigo-600"></i>
                                    Date de naissance
                                </label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->student->date_of_birth ?? '') }}"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Parent -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-friends mr-2 text-indigo-600"></i>
                                    Parent (optionnel)
                                </label>
                                <select name="parent_id"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Aucun parent</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id', $user->student->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i>
                                Adresse
                            </label>
                            <textarea name="address" rows="2"
                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                      placeholder="Adresse complète">{{ old('address', $user->student->address ?? '') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Teacher-specific fields -->
                <div id="teacherFields" class="hidden space-y-6">
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Enseignant</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-book mr-2 text-indigo-600"></i>
                                Matières enseignées
                            </label>
                            <p class="text-sm text-gray-500 mb-2">Gérez les matières depuis la page des matières</p>
                        </div>
                    </div>
                </div>

                <!-- Parent-specific fields -->
                <div id="parentFields" class="hidden space-y-6">
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Parent</h3>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Relationship -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heart mr-2 text-indigo-600"></i>
                                    Relation
                                </label>
                                <select name="relationship"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Sélectionner</option>
                                    <option value="Père" {{ old('relationship', $user->parentProfile->relationship ?? '') == 'Père' ? 'selected' : '' }}>Père</option>
                                    <option value="Mère" {{ old('relationship', $user->parentProfile->relationship ?? '') == 'Mère' ? 'selected' : '' }}>Mère</option>
                                    <option value="Tuteur" {{ old('relationship', $user->parentProfile->relationship ?? '') == 'Tuteur' ? 'selected' : '' }}>Tuteur</option>
                                    <option value="Tutrice" {{ old('relationship', $user->parentProfile->relationship ?? '') == 'Tutrice' ? 'selected' : '' }}>Tutrice</option>
                                </select>
                                @error('relationship')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 rounded-b-xl">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('directeur.users.index') }}" 
                       class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide role-specific fields
    document.getElementById('role').addEventListener('change', function() {
        const role = this.value;
        
        // Hide all role-specific sections
        document.getElementById('studentFields').classList.add('hidden');
        document.getElementById('teacherFields').classList.add('hidden');
        document.getElementById('parentFields').classList.add('hidden');
        
        // Show relevant section
        if (role === 'etudiant') {
            document.getElementById('studentFields').classList.remove('hidden');
            // Make student fields required
            document.querySelector('[name="matricule"]').required = true;
            document.querySelector('[name="class_id"]').required = true;
        } else if (role === 'enseignant') {
            document.getElementById('teacherFields').classList.remove('hidden');
        } else if (role === 'parent') {
            document.getElementById('parentFields').classList.remove('hidden');
        }
    });
    
    // Trigger on page load
    document.getElementById('role').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
