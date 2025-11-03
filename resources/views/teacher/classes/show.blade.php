@extends('layouts.modern')

@section('title', 'Détails de la Classe')
@section('breadcrumb', 'Classe - ' . $class->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $class->name }}</h1>
                <p class="mt-2 text-sm text-gray-600">Détails de la classe et liste des étudiants</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('enseignant.attendance.create') }}" 
                   class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-500 transition-colors">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Marquer Présence
                </a>
                <a href="{{ route('enseignant.grades.create') }}" 
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter Note
                </a>
            </div>
        </div>
    </div>

    <!-- Class Statistics -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 mb-8">
        <div class="rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Étudiants</p>
                    <p class="mt-2 text-4xl font-bold">{{ $class->students->count() }}</p>
                </div>
                <i class="fas fa-users text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Moyenne Classe</p>
                    <p class="mt-2 text-4xl font-bold">
                        @php
                            $classAverage = $class->students->flatMap->grades->avg('grade_value');
                        @endphp
                        {{ number_format($classAverage ?? 0, 2) }}
                    </p>
                </div>
                <i class="fas fa-chart-line text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-blue-500 to-cyan-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Taux Présence</p>
                    <p class="mt-2 text-4xl font-bold">
                        @php
                            $totalAttendance = $class->students->flatMap->attendance->count();
                            $presentCount = $class->students->flatMap->attendance->where('status', 'present')->count();
                            $attendanceRate = $totalAttendance > 0 ? ($presentCount / $totalAttendance) * 100 : 0;
                        @endphp
                        {{ number_format($attendanceRate, 1) }}%
                    </p>
                </div>
                <i class="fas fa-calendar-check text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-pink-500 to-rose-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Matières</p>
                    <p class="mt-2 text-4xl font-bold">{{ $class->subjects->count() }}</p>
                </div>
                <i class="fas fa-book text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
        <div class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchStudent" 
                           class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Rechercher un étudiant...">
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">Liste des Étudiants</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Étudiant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Matricule</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Moyenne</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Présence</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white" id="studentsTableBody">
                    @forelse($class->students as $student)
                        <tr class="hover:bg-gray-50 transition-colors student-row">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 student-name">{{ $student->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $student->matricule }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $student->user->email }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $average = $student->grades->avg('grade_value');
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $average >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($average ?? 0, 2) }}/20
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $total = $student->attendance->count();
                                    $present = $student->attendance->where('status', 'present')->count();
                                    $rate = $total > 0 ? ($present / $total) * 100 : 0;
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $rate >= 80 ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                    {{ number_format($rate, 1) }}%
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('enseignant.students.show', $student) }}" 
                                       class="rounded-lg bg-indigo-50 p-2 text-indigo-600 hover:bg-indigo-100 transition-colors" 
                                       title="Voir profil">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-user-graduate text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900">Aucun étudiant dans cette classe</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchStudent').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        
        rows.forEach(row => {
            const name = row.querySelector('.student-name').textContent.toLowerCase();
            if (name.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection
