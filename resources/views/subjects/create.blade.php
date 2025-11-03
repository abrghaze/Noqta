@extends('layouts.modern')

@section('title', 'Créer une Matière')
@section('breadcrumb', 'Créer une Matière')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Créer une Nouvelle Matière</h1>
                <p class="mt-2 text-sm text-gray-600">Ajouter une matière au système</p>
            </div>
            <a href="{{ route('directeur.subjects.index') }}" 
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl bg-white shadow-sm max-w-3xl">
        <form method="POST" action="{{ route('directeur.subjects.store') }}">
            @csrf

            <div class="p-6 space-y-6">
                <!-- Subject Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book mr-2 text-indigo-600"></i>
                        Nom de la matière <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Ex: Mathématiques, Physique, Histoire, etc.">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Class Assignment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-door-open mr-2 text-indigo-600"></i>
                            Classe <span class="text-red-500">*</span>
                        </label>
                        <select name="class_id" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teacher Assignment -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-chalkboard-teacher mr-2 text-indigo-600"></i>
                            Enseignant <span class="text-red-500">*</span>
                        </label>
                        <select name="teacher_id" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Sélectionner un enseignant</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Coefficient -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-balance-scale mr-2 text-indigo-600"></i>
                        Coefficient <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="coefficient" value="{{ old('coefficient', 1) }}" 
                           step="0.5" min="0.5" max="10" required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Ex: 1, 2, 3">
                    @error('coefficient')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Le coefficient détermine l'importance de la matière dans le calcul de la moyenne (0.5 à 10)</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-indigo-600"></i>
                        Description (optionnel)
                    </label>
                    <textarea name="description" rows="4"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              placeholder="Description de la matière, objectifs, contenu du programme...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maximum 1000 caractères</p>
                </div>

                <!-- Info Box -->
                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mr-3 text-xl mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Information</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                L'enseignant assigné pourra gérer les notes et les présences pour cette matière. Le coefficient sera utilisé pour calculer la moyenne générale des étudiants.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 rounded-b-xl">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('directeur.subjects.index') }}" 
                       class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Créer la matière
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
