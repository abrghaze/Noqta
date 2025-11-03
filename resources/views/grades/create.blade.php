@extends('layouts.modern')

@section('title', 'Ajouter des Notes')
@section('breadcrumb', 'Ajouter des Notes')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Ajouter des Notes</h1>
        <p class="mt-2 text-sm text-gray-600">Saisir les notes des étudiants</p>
    </div>

    <!-- Form Card -->
    <div class="max-w-3xl mx-auto">
        <div class="rounded-xl bg-white p-8 shadow-sm">
            <form method="POST" action="{{ route('enseignant.grades.store') }}" class="space-y-6">
                @csrf

                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-graduate mr-2 text-indigo-600"></i>
                        Étudiant <span class="text-red-500">*</span>
                    </label>
                    <select name="student_id" id="student_id" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('student_id') border-red-500 @enderror">
                        <option value="">Sélectionner un étudiant</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book mr-2 text-green-600"></i>
                        Matière <span class="text-red-500">*</span>
                    </label>
                    <select name="subject_id" id="subject_id" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('subject_id') border-red-500 @enderror">
                        <option value="">Sélectionner une matière</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} - {{ $subject->class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grade Value and Max Grade -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="grade_value" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-star mr-2 text-yellow-600"></i>
                            Note obtenue <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="grade_value" id="grade_value" required
                               value="{{ old('grade_value') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('grade_value') border-red-500 @enderror"
                               placeholder="Ex: 15.5">
                        @error('grade_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_grade" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-trophy mr-2 text-purple-600"></i>
                            Note maximale <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="max_grade" id="max_grade" required
                               value="{{ old('max_grade', '20') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('max_grade') border-red-500 @enderror"
                               placeholder="Ex: 20">
                        @error('max_grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Exam Type and Date -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="exam_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-clipboard-list mr-2 text-blue-600"></i>
                            Type d'évaluation <span class="text-red-500">*</span>
                        </label>
                        <select name="exam_type" id="exam_type" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('exam_type') border-red-500 @enderror">
                            <option value="">Sélectionner le type</option>
                            <option value="Composition" {{ old('exam_type') == 'Composition' ? 'selected' : '' }}>Composition</option>
                            <option value="Devoir" {{ old('exam_type') == 'Devoir' ? 'selected' : '' }}>Devoir</option>
                            <option value="Contrôle" {{ old('exam_type') == 'Contrôle' ? 'selected' : '' }}>Contrôle</option>
                        </select>
                        @error('exam_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-red-600"></i>
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                               value="{{ old('date', date('Y-m-d')) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Comment -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2 text-gray-600"></i>
                        Commentaire (optionnel)
                    </label>
                    <textarea name="comment" id="comment" rows="3"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('comment') border-red-500 @enderror"
                              placeholder="Ajouter un commentaire sur cette note...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('enseignant.grades.index') }}" 
                       class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer la note
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="mt-6 rounded-lg bg-blue-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Conseils</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Vérifiez bien l'étudiant et la matière avant de soumettre</li>
                            <li>La note doit être comprise entre 0 et la note maximale</li>
                            <li>Vous pouvez ajouter un commentaire pour justifier la note</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-calculate percentage
    document.getElementById('grade_value').addEventListener('input', function() {
        const gradeValue = parseFloat(this.value) || 0;
        const maxGrade = parseFloat(document.getElementById('max_grade').value) || 20;
        const percentage = maxGrade > 0 ? ((gradeValue / maxGrade) * 100).toFixed(1) : 0;
        
        // You can display this percentage somewhere if needed
        console.log('Percentage:', percentage + '%');
    });
</script>
@endpush
@endsection
