@extends('layouts.modern')

@section('title', 'Mon Profil')
@section('breadcrumb', 'Profil')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 border-l-4 border-green-400">
            <div class="flex">
                <i class="fas fa-check-circle text-green-400 text-xl"></i>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile Header -->
    <div class="mb-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row items-center">
            <!-- Profile Picture -->
            <div class="relative group">
                <div class="h-32 w-32 rounded-full bg-white/20 flex items-center justify-center text-5xl font-bold border-4 border-white shadow-xl">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile" class="h-full w-full rounded-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    @endif
                </div>
                <button type="button" onclick="document.getElementById('profile_picture').click()" 
                        class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-camera text-white text-2xl"></i>
                </button>
            </div>

            <!-- Profile Info -->
            <div class="ml-0 sm:ml-8 mt-4 sm:mt-0 text-center sm:text-left flex-1">
                <h1 class="text-3xl font-bold">{{ auth()->user()->name }}</h1>
                <div class="mt-2 flex flex-wrap items-center justify-center sm:justify-start gap-3">
                    @php
                        $roleColors = [
                            'directeur' => 'bg-red-500',
                            'enseignant' => 'bg-green-500',
                            'etudiant' => 'bg-purple-500',
                            'parent' => 'bg-blue-500',
                        ];
                        $roleIcons = [
                            'directeur' => 'fa-user-tie',
                            'enseignant' => 'fa-chalkboard-teacher',
                            'etudiant' => 'fa-user-graduate',
                            'parent' => 'fa-users',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full {{ $roleColors[auth()->user()->role] ?? 'bg-gray-500' }} px-4 py-1 text-sm font-semibold text-white">
                        <i class="fas {{ $roleIcons[auth()->user()->role] ?? 'fa-user' }} mr-2"></i>
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                    <span class="inline-flex items-center text-sm">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ auth()->user()->email }}
                    </span>
                    <span class="inline-flex items-center text-sm">
                        <i class="fas fa-calendar mr-2"></i>
                        Membre depuis {{ auth()->user()->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Profile Information Card -->
        <div class="lg:col-span-2">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-bold text-gray-900">Informations du Profil</h2>
                    <button type="button" id="editProfileBtn" onclick="toggleEditMode()" 
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Modifier
                    </button>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PATCH')

                    <!-- Hidden file input -->
                    <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" onchange="previewImage(this)">

                    <!-- Name -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-indigo-600"></i>
                            Nom Complet <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm profile-input" disabled>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-indigo-600"></i>
                            Adresse Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm profile-input" disabled>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone (optional) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-indigo-600"></i>
                            Téléphone
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm profile-input" disabled>
                    </div>

                    <!-- Role-specific fields -->
                    @if(auth()->user()->role === 'etudiant' && auth()->user()->student)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-2 text-indigo-600"></i>
                                Matricule
                            </label>
                            <input type="text" value="{{ auth()->user()->student->matricule }}" class="block w-full rounded-lg border-gray-300 bg-gray-50 sm:text-sm" disabled>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-door-open mr-2 text-indigo-600"></i>
                                Classe
                            </label>
                            <input type="text" value="{{ auth()->user()->student->class->name ?? 'Non assigné' }}" class="block w-full rounded-lg border-gray-300 bg-gray-50 sm:text-sm" disabled>
                        </div>
                    @endif

                    <!-- Action Buttons (hidden by default) -->
                    <div id="profileActions" class="hidden pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-end space-x-4">
                            <button type="button" onclick="toggleEditMode()" 
                                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-times mr-2"></i>
                                Annuler
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Change Password Card -->
            <div class="mt-6 rounded-xl bg-white p-6 shadow-sm">
                <div class="mb-6 border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-bold text-gray-900">Changer le Mot de Passe</h2>
                    <p class="text-sm text-gray-500">Assurez-vous d'utiliser un mot de passe fort</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe actuel <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="current_password" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nouveau mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmer le mot de passe <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                        <i class="fas fa-key mr-2"></i>
                        Mettre à jour le mot de passe
                    </button>
                </form>
            </div>
        </div>

        <!-- Statistics Sidebar -->
        <div class="lg:col-span-1">
            @if(auth()->user()->role === 'etudiant' && auth()->user()->student)
                <!-- Student Statistics -->
                <div class="rounded-xl bg-white p-6 shadow-sm mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Mes Statistiques</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-purple-50">
                            <div class="flex items-center">
                                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                                <span class="ml-3 text-sm font-medium text-gray-700">Moyenne</span>
                            </div>
                            <span class="text-lg font-bold text-purple-600">
                                {{ number_format(auth()->user()->student->grades->avg('grade_value') ?? 0, 2) }}/20
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-green-50">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                                <span class="ml-3 text-sm font-medium text-gray-700">Présence</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">
                                @php
                                    $total = auth()->user()->student->attendance->count();
                                    $present = auth()->user()->student->attendance->where('status', 'present')->count();
                                    $rate = $total > 0 ? ($present / $total) * 100 : 0;
                                @endphp
                                {{ number_format($rate, 1) }}%
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50">
                            <div class="flex items-center">
                                <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                                <span class="ml-3 text-sm font-medium text-gray-700">Notes</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">
                                {{ auth()->user()->student->grades->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->role === 'enseignant' && auth()->user()->teacher)
                <!-- Teacher Statistics -->
                <div class="rounded-xl bg-white p-6 shadow-sm mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Mes Statistiques</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-purple-50">
                            <div class="flex items-center">
                                <i class="fas fa-door-open text-purple-600 text-xl"></i>
                                <span class="ml-3 text-sm font-medium text-gray-700">Classes</span>
                            </div>
                            <span class="text-lg font-bold text-purple-600">
                                {{ \App\Models\ClassRoom::where('teacher_id', auth()->id())->count() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-green-50">
                            <div class="flex items-center">
                                <i class="fas fa-book text-green-600 text-xl"></i>
                                <span class="ml-3 text-sm font-medium text-gray-700">Matières</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">
                                {{ \App\Models\Subject::where('teacher_id', auth()->id())->count() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50">
                            <div class="flex items-center">
                                <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                                <span class="ml-3 text-sm font-medium text-gray-700">Étudiants</span>
                            </div>
                            <span class="text-lg font-bold text-blue-600">
                                @php
                                    $classIds = \App\Models\ClassRoom::where('teacher_id', auth()->id())->pluck('id');
                                    $studentCount = \App\Models\Student::whereIn('class_id', $classIds)->count();
                                @endphp
                                {{ $studentCount }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Account Info -->
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informations du Compte</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-calendar-plus w-5 text-gray-400"></i>
                        <span class="ml-3">Créé le {{ auth()->user()->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-clock w-5 text-gray-400"></i>
                        <span class="ml-3">Dernière connexion: {{ auth()->user()->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-shield-alt w-5 text-gray-400"></i>
                        <span class="ml-3">Compte {{ auth()->user()->email_verified_at ? 'vérifié' : 'non vérifié' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleEditMode() {
        const inputs = document.querySelectorAll('.profile-input');
        const actions = document.getElementById('profileActions');
        const editBtn = document.getElementById('editProfileBtn');
        
        inputs.forEach(input => {
            input.disabled = !input.disabled;
            if (!input.disabled) {
                input.classList.remove('bg-gray-50');
                input.classList.add('bg-white');
            } else {
                input.classList.remove('bg-white');
                input.classList.add('bg-gray-50');
            }
        });
        
        actions.classList.toggle('hidden');
        editBtn.classList.toggle('hidden');
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Submit form automatically when image is selected
                document.getElementById('profileForm').submit();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
