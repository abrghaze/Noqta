@extends('layouts.modern')

@section('title', 'Modifier la Présence')
@section('breadcrumb', 'Modifier la Présence')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier la Présence</h1>
                <p class="mt-2 text-sm text-gray-600">Mettre à jour le statut de présence</p>
            </div>
            <a href="{{ route('enseignant.attendance.index') }}" 
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl bg-white shadow-sm">
        <form method="POST" action="{{ route('enseignant.attendance.update', $attendance) }}">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Student Info (Read-only) -->
                <div class="rounded-lg bg-gray-50 border border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-user-graduate text-indigo-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $attendance->student->user->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $attendance->student->class->name ?? 'N/A' }} - {{ $attendance->student->matricule }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subject Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-book mr-2 text-indigo-600"></i>
                        Matière <span class="text-red-500">*</span>
                    </label>
                    <select name="subject_id" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Sélectionner une matière</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $attendance->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} - {{ $subject->class->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-indigo-600"></i>
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" value="{{ old('date', $attendance->date) }}" required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle mr-2 text-indigo-600"></i>
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <!-- Present -->
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-indigo-500 focus:outline-none {{ old('status', $attendance->status) == 'present' ? 'border-indigo-600 ring-2 ring-indigo-600' : '' }}">
                            <input type="radio" name="status" value="present" {{ old('status', $attendance->status) == 'present' ? 'checked' : '' }} required
                                   class="sr-only">
                            <div class="flex flex-col items-center w-full">
                                <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                                <span class="text-sm font-semibold text-gray-900">Présent</span>
                            </div>
                        </label>

                        <!-- Absent -->
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-indigo-500 focus:outline-none {{ old('status', $attendance->status) == 'absent' ? 'border-indigo-600 ring-2 ring-indigo-600' : '' }}">
                            <input type="radio" name="status" value="absent" {{ old('status', $attendance->status) == 'absent' ? 'checked' : '' }} required
                                   class="sr-only">
                            <div class="flex flex-col items-center w-full">
                                <i class="fas fa-times-circle text-3xl text-red-500 mb-2"></i>
                                <span class="text-sm font-semibold text-gray-900">Absent</span>
                            </div>
                        </label>

                        <!-- Late -->
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-indigo-500 focus:outline-none {{ old('status', $attendance->status) == 'late' ? 'border-indigo-600 ring-2 ring-indigo-600' : '' }}">
                            <input type="radio" name="status" value="late" {{ old('status', $attendance->status) == 'late' ? 'checked' : '' }} required
                                   class="sr-only">
                            <div class="flex flex-col items-center w-full">
                                <i class="fas fa-clock text-3xl text-yellow-500 mb-2"></i>
                                <span class="text-sm font-semibold text-gray-900">En retard</span>
                            </div>
                        </label>

                        <!-- Excused -->
                        <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-indigo-500 focus:outline-none {{ old('status', $attendance->status) == 'excused' ? 'border-indigo-600 ring-2 ring-indigo-600' : '' }}">
                            <input type="radio" name="status" value="excused" {{ old('status', $attendance->status) == 'excused' ? 'checked' : '' }} required
                                   class="sr-only">
                            <div class="flex flex-col items-center w-full">
                                <i class="fas fa-file-medical text-3xl text-blue-500 mb-2"></i>
                                <span class="text-sm font-semibold text-gray-900">Excusé</span>
                            </div>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2 text-indigo-600"></i>
                        Raison (optionnel)
                    </label>
                    <textarea name="reason" rows="3"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              placeholder="Ex: Malade, Rendez-vous médical, etc.">{{ old('reason', $attendance->reason) }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maximum 500 caractères</p>
                </div>

                <!-- Info Box -->
                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mr-3 text-xl mt-0.5"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Information</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Les modifications de présence seront immédiatement visibles pour l'étudiant et ses parents.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 rounded-b-xl">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('enseignant.attendance.index') }}" 
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
    // Add visual feedback for radio button selection
    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove all ring classes
            document.querySelectorAll('label[class*="ring-2"]').forEach(label => {
                label.classList.remove('border-indigo-600', 'ring-2', 'ring-indigo-600');
                label.classList.add('border-gray-300');
            });
            
            // Add ring to selected
            if (this.checked) {
                const label = this.closest('label');
                label.classList.remove('border-gray-300');
                label.classList.add('border-indigo-600', 'ring-2', 'ring-indigo-600');
            }
        });
    });
</script>
@endpush
@endsection
