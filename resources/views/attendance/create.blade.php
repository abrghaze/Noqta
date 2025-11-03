@extends('layouts.modern')

@section('title', 'Marquer Présence')
@section('breadcrumb', 'Marquer Présence')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Marquer Présence</h1>
        <p class="mt-2 text-sm text-gray-600">Enregistrer les présences et absences</p>
    </div>

    <!-- Form Card -->
    <div class="max-w-5xl mx-auto">
        <form method="POST" action="{{ route('enseignant.attendance.store') }}" x-data="attendanceForm()">
            @csrf

            <!-- Selection Card -->
            <div class="rounded-xl bg-white p-6 shadow-sm mb-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <!-- Subject Selection -->
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book mr-2 text-green-600"></i>
                            Matière <span class="text-red-500">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" required x-model="selectedSubject" @change="loadStudents()"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Sélectionner une matière</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" data-class-id="{{ $subject->class_id }}">
                                    {{ $subject->name }} - {{ $subject->class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Selection -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-blue-600"></i>
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                               value="{{ date('Y-m-d') }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex items-end">
                        <div class="flex space-x-2 w-full">
                            <button type="button" @click="markAll('present')"
                                    class="flex-1 rounded-lg bg-green-100 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-200 transition-colors">
                                <i class="fas fa-check mr-1"></i> Tous présents
                            </button>
                            <button type="button" @click="markAll('absent')"
                                    class="flex-1 rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200 transition-colors">
                                <i class="fas fa-times mr-1"></i> Tous absents
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students List -->
            <div class="rounded-xl bg-white shadow-sm overflow-hidden" x-show="selectedSubject">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Liste des Étudiants</h3>
                    <p class="text-sm text-gray-500">Marquer la présence pour chaque étudiant</p>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach($classes as $class)
                        @foreach($class->students as $student)
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors" 
                                 x-show="selectedSubject && getClassId(selectedSubject) == {{ $class->id }}">
                                <div class="flex items-center justify-between">
                                    <!-- Student Info -->
                                    <div class="flex items-center flex-1">
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $student->user->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $student->matricule }}</p>
                                        </div>
                                    </div>

                                    <!-- Status Selection -->
                                    <div class="flex items-center space-x-2">
                                        <input type="hidden" name="attendance[{{ $loop->parent->index * 100 + $loop->index }}][student_id]" value="{{ $student->id }}">
                                        
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" 
                                                   name="attendance[{{ $loop->parent->index * 100 + $loop->index }}][status]" 
                                                   value="present" 
                                                   class="sr-only peer"
                                                   x-model="students[{{ $student->id }}]">
                                            <div class="px-4 py-2 rounded-lg border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 transition-all">
                                                <i class="fas fa-check mr-1"></i>
                                                <span class="text-sm font-medium">Présent</span>
                                            </div>
                                        </label>

                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" 
                                                   name="attendance[{{ $loop->parent->index * 100 + $loop->index }}][status]" 
                                                   value="absent" 
                                                   class="sr-only peer"
                                                   x-model="students[{{ $student->id }}]">
                                            <div class="px-4 py-2 rounded-lg border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition-all">
                                                <i class="fas fa-times mr-1"></i>
                                                <span class="text-sm font-medium">Absent</span>
                                            </div>
                                        </label>

                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" 
                                                   name="attendance[{{ $loop->parent->index * 100 + $loop->index }}][status]" 
                                                   value="late" 
                                                   class="sr-only peer"
                                                   x-model="students[{{ $student->id }}]">
                                            <div class="px-4 py-2 rounded-lg border-2 border-gray-200 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:text-yellow-700 transition-all">
                                                <i class="fas fa-clock mr-1"></i>
                                                <span class="text-sm font-medium">Retard</span>
                                            </div>
                                        </label>

                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" 
                                                   name="attendance[{{ $loop->parent->index * 100 + $loop->index }}][status]" 
                                                   value="excused" 
                                                   class="sr-only peer"
                                                   x-model="students[{{ $student->id }}]">
                                            <div class="px-4 py-2 rounded-lg border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                <span class="text-sm font-medium">Excusé</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Reason field (shown for absent/late/excused) -->
                                <div class="mt-3" x-show="students[{{ $student->id }}] && students[{{ $student->id }}] !== 'present'">
                                    <input type="text" 
                                           name="attendance[{{ $loop->parent->index * 100 + $loop->index }}][reason]"
                                           placeholder="Raison (optionnel)"
                                           class="block w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('enseignant.attendance.index') }}" 
                           class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                        <button type="submit"
                                class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Enregistrer les présences
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div x-show="!selectedSubject" class="rounded-xl bg-white p-12 text-center shadow-sm">
                <i class="fas fa-hand-pointer text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">Sélectionnez une matière</h3>
                <p class="mt-2 text-sm text-gray-500">Choisissez une matière pour afficher la liste des étudiants</p>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function attendanceForm() {
        return {
            selectedSubject: '',
            students: {},
            
            getClassId(subjectId) {
                const select = document.getElementById('subject_id');
                const option = select.querySelector(`option[value="${subjectId}"]`);
                return option ? option.dataset.classId : null;
            },
            
            loadStudents() {
                // Reset students when subject changes
                this.students = {};
            },
            
            markAll(status) {
                // Mark all visible students with the given status
                Object.keys(this.students).forEach(studentId => {
                    this.students[studentId] = status;
                });
                
                // Also set all radio buttons
                const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
                radios.forEach(radio => {
                    const container = radio.closest('[x-show]');
                    if (!container || container.style.display !== 'none') {
                        radio.checked = true;
                    }
                });
            }
        }
    }
</script>
@endpush
@endsection
