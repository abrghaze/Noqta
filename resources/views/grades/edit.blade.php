@extends('layouts.modern')

@section('title', 'Modifier la Note')
@section('breadcrumb', 'Modifier la Note')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier la Note</h1>
                <p class="mt-2 text-sm text-gray-600">Mettre à jour les informations de la note</p>
            </div>
            <a href="{{ route('enseignant.grades.index') }}" 
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl bg-white shadow-sm">
        <form method="POST" action="{{ route('enseignant.grades.update', $grade) }}">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Student Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-graduate mr-2 text-indigo-600"></i>
                        Étudiant <span class="text-red-500">*</span>
                    </label>
                    <select name="student_id" id="student_id" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Sélectionner un étudiant</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $grade->student_id) == $student->id ? 'selected' : '' }}>
                                {{ $student->user->name }} - {{ $student->class->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book mr-2 text-indigo-600"></i>
                        Matière <span class="text-red-500">*</span>
                    </label>
                    <select name="subject_id" id="subject_id" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Sélectionner une matière</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $grade->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} - {{ $subject->class->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grade Information -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Grade Value -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-star mr-2 text-indigo-600"></i>
                            Note obtenue <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="grade_value" value="{{ old('grade_value', $grade->grade_value) }}" 
                               step="0.01" min="0" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ex: 15.5">
                        @error('grade_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Grade -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-trophy mr-2 text-indigo-600"></i>
                            Note maximale <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="max_grade" value="{{ old('max_grade', $grade->max_grade) }}" 
                               step="0.01" min="0" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Ex: 20">
                        @error('max_grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exam Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clipboard-list mr-2 text-indigo-600"></i>
                            Type d'examen <span class="text-red-500">*</span>
                        </label>
                        <select name="exam_type" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Sélectionner un type</option>
                            <option value="Composition" {{ old('exam_type', $grade->exam_type) == 'Composition' ? 'selected' : '' }}>Composition</option>
                            <option value="Devoir" {{ old('exam_type', $grade->exam_type) == 'Devoir' ? 'selected' : '' }}>Devoir</option>
                            <option value="Contrôle" {{ old('exam_type', $grade->exam_type) == 'Contrôle' ? 'selected' : '' }}>Contrôle</option>
                        </select>
                        @error('exam_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-indigo-600"></i>
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" value="{{ old('date', $grade->date) }}" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Comment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2 text-indigo-600"></i>
                        Commentaire (optionnel)
                    </label>
                    <textarea name="comment" rows="3"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              placeholder="Ajouter un commentaire sur la performance de l'étudiant...">{{ old('comment', $grade->comment) }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maximum 500 caractères</p>
                </div>

                <!-- Grade Preview -->
                <div class="rounded-lg bg-indigo-50 border border-indigo-200 p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-indigo-600 mr-3 text-xl"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-indigo-900">Aperçu de la note</h4>
                            <p class="text-sm text-indigo-700 mt-1">
                                La note sera calculée automatiquement en pourcentage et affichée aux étudiants et parents.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 rounded-b-xl">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('enseignant.grades.index') }}" 
                       class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour la note
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
