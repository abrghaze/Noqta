@extends('layouts.modern')

@section('title', 'Profil Étudiant')
@section('breadcrumb', 'Étudiant - ' . $student->user->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Student Header -->
    <div class="mb-8 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 p-8 text-white shadow-lg">
        <div class="flex flex-col sm:flex-row items-center">
            <div class="h-32 w-32 rounded-full bg-white/20 flex items-center justify-center text-5xl font-bold border-4 border-white shadow-xl">
                {{ strtoupper(substr($student->user->name, 0, 2)) }}
            </div>
            <div class="ml-0 sm:ml-8 mt-4 sm:mt-0 text-center sm:text-left flex-1">
                <h1 class="text-3xl font-bold">{{ $student->user->name }}</h1>
                <div class="mt-2 flex flex-wrap items-center justify-center sm:justify-start gap-3">
                    <span class="inline-flex items-center text-sm">
                        <i class="fas fa-id-card mr-2"></i>
                        {{ $student->matricule }}
                    </span>
                    <span class="inline-flex items-center text-sm">
                        <i class="fas fa-door-open mr-2"></i>
                        {{ $student->class->name }}
                    </span>
                    <span class="inline-flex items-center text-sm">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ $student->user->email }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <a href="{{ route('enseignant.grades.create') }}?student_id={{ $student->id }}" 
           class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-4">
                    <i class="fas fa-plus-circle text-2xl text-green-600"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Ajouter une Note</h3>
                    <p class="text-sm text-gray-500">Saisir une nouvelle note pour cet étudiant</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
            </div>
        </a>

        <a href="{{ route('enseignant.attendance.create') }}" 
           class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="rounded-full bg-blue-100 p-4">
                    <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Marquer Présence</h3>
                    <p class="text-sm text-gray-500">Enregistrer la présence de cet étudiant</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
            </div>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 mb-8">
        <div class="rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Moyenne</p>
                    <p class="mt-2 text-4xl font-bold">{{ number_format($student->grades->avg('grade_value') ?? 0, 2) }}</p>
                </div>
                <i class="fas fa-chart-line text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Taux Présence</p>
                    <p class="mt-2 text-4xl font-bold">
                        @php
                            $total = $student->attendance->count();
                            $present = $student->attendance->where('status', 'present')->count();
                            $rate = $total > 0 ? ($present / $total) * 100 : 0;
                        @endphp
                        {{ number_format($rate, 1) }}%
                    </p>
                </div>
                <i class="fas fa-calendar-check text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-blue-500 to-cyan-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Notes</p>
                    <p class="mt-2 text-4xl font-bold">{{ $student->grades->count() }}</p>
                </div>
                <i class="fas fa-clipboard-list text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-pink-500 to-rose-700 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Absences</p>
                    <p class="mt-2 text-4xl font-bold">{{ $student->attendance->where('status', 'absent')->count() }}</p>
                </div>
                <i class="fas fa-times-circle text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Grades and Attendance -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Grades -->
        <div class="rounded-xl bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Notes Récentes</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($student->grades->sortByDesc('date')->take(10) as $grade)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $grade->subject->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $grade->exam_type }} - {{ $grade->date->format('d/m/Y') }}</p>
                                @if($grade->comment)
                                    <p class="text-xs text-gray-600 mt-1">{{ $grade->comment }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $grade->grade_value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $grade->grade_value }}/{{ $grade->max_grade }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Aucune note disponible</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="rounded-xl bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">Présences Récentes</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($student->attendance->sortByDesc('date')->take(10) as $record)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $record->subject->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $record->date->format('d/m/Y') }}</p>
                                @if($record->reason)
                                    <p class="text-xs text-gray-600 mt-1">{{ $record->reason }}</p>
                                @endif
                            </div>
                            @if($record->status === 'present')
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">
                                    <i class="fas fa-check mr-1"></i> Présent
                                </span>
                            @elseif($record->status === 'absent')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800">
                                    <i class="fas fa-times mr-1"></i> Absent
                                </span>
                            @elseif($record->status === 'late')
                                <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Retard
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">
                                    <i class="fas fa-info mr-1"></i> Excusé
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-calendar-check text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500">Aucun enregistrement</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
