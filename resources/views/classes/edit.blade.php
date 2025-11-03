@extends('layouts.modern')

@section('title', 'Modifier la Classe')
@section('breadcrumb', 'Modifier la Classe')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier la Classe</h1>
                <p class="mt-2 text-sm text-gray-600">Mettre à jour les informations de {{ $class->name }}</p>
            </div>
            <a href="{{ route('directeur.classes.index') }}" 
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl bg-white shadow-sm max-w-3xl">
        <form method="POST" action="{{ route('directeur.classes.update', $class) }}">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Class Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-door-open mr-2 text-indigo-600"></i>
                        Nom de la classe <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $class->name) }}" required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Ex: 1ère Année A, Terminale S1, etc.">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Le nom doit être unique</p>
                </div>

                <!-- Teacher Assignment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-chalkboard-teacher mr-2 text-indigo-600"></i>
                        Enseignant principal (optionnel)
                    </label>
                    <select name="teacher_id"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Aucun enseignant assigné</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} ({{ $teacher->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">L'enseignant principal sera responsable de cette classe</p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-2 text-indigo-600"></i>
                            Capacité (optionnel)
                        </label>
                        <input type="number" name="capacity" value="{{ old('capacity', $class->capacity) }}" min="1"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ex: 30">
                        @error('capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Nombre maximum d'étudiants</p>
                    </div>

                    <!-- Room Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i>
                            Numéro de salle (optionnel)
                        </label>
                        <input type="text" name="room_number" value="{{ old('room_number', $class->room_number) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ex: A101, B205">
                        @error('room_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Localisation de la salle de classe</p>
                    </div>
                </div>

                <!-- Class Statistics -->
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Statistiques de la classe</h4>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                        <div>
                            <p class="text-xs text-gray-500">Étudiants</p>
                            <p class="text-lg font-bold text-gray-900">{{ $class->students()->count() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Matières</p>
                            <p class="text-lg font-bold text-gray-900">{{ $class->subjects()->count() }}</p>
                        </div>
                        @if($class->capacity)
                            <div>
                                <p class="text-xs text-gray-500">Places restantes</p>
                                <p class="text-lg font-bold text-gray-900">{{ max(0, $class->capacity - $class->students()->count()) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Box -->
                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mr-3 text-xl mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Information</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Les étudiants et matières de cette classe peuvent être gérés depuis leurs pages respectives.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 rounded-b-xl">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('directeur.classes.index') }}" 
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
@endsection
