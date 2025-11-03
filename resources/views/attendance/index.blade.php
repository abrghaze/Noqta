@extends('layouts.modern')

@section('title', 'Gestion des Absences')
@section('breadcrumb', 'Absences')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                @if(auth()->user()->role === 'enseignant')
                    Gestion des Présences
                @elseif(auth()->user()->role === 'etudiant')
                    Mes Absences
                @elseif(auth()->user()->role === 'parent')
                    Absences de {{ auth()->user()->parentProfile->student->user->name }}
                @else
                    Toutes les Absences
                @endif
            </h1>
            <p class="mt-2 text-sm text-gray-600">Consulter et gérer les présences</p>
        </div>
        @if(auth()->user()->role === 'enseignant')
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('enseignant.attendance.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Marquer Présence
                </a>
            </div>
        @endif
    </div>

    <!-- Stats Cards (for students/parents) -->
    @if(in_array(auth()->user()->role, ['etudiant', 'parent']))
        @php
            $totalRecords = $attendance->count();
            $presentCount = $attendance->where('status', 'present')->count();
            $absentCount = $attendance->where('status', 'absent')->count();
            $lateCount = $attendance->where('status', 'late')->count();
            $attendanceRate = $totalRecords > 0 ? ($presentCount / $totalRecords) * 100 : 0;
        @endphp
        
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 mb-8">
            <div class="rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Taux de Présence</p>
                        <p class="mt-2 text-4xl font-bold">{{ number_format($attendanceRate, 1) }}%</p>
                    </div>
                    <i class="fas fa-chart-pie text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="rounded-xl bg-gradient-to-br from-blue-500 to-cyan-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Présent</p>
                        <p class="mt-2 text-4xl font-bold">{{ $presentCount }}</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="rounded-xl bg-gradient-to-br from-red-500 to-rose-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Absent</p>
                        <p class="mt-2 text-4xl font-bold">{{ $absentCount }}</p>
                    </div>
                    <i class="fas fa-times-circle text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="rounded-xl bg-gradient-to-br from-yellow-500 to-orange-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Retards</p>
                        <p class="mt-2 text-4xl font-bold">{{ $lateCount }}</p>
                    </div>
                    <i class="fas fa-clock text-4xl opacity-50"></i>
                </div>
            </div>
        </div>
    @endif

    <!-- Attendance Table -->
    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @if(auth()->user()->role === 'enseignant')
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Étudiant</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Raison</th>
                        @if(auth()->user()->role === 'enseignant')
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($attendance as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            @if(auth()->user()->role === 'enseignant')
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($record->student->user->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $record->student->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $record->student->class->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $record->subject->name }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if($record->status === 'present')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                        <i class="fas fa-check mr-1.5"></i> Présent
                                    </span>
                                @elseif($record->status === 'absent')
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                        <i class="fas fa-times mr-1.5"></i> Absent
                                    </span>
                                @elseif($record->status === 'late')
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                                        <i class="fas fa-clock mr-1.5"></i> Retard
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                        <i class="fas fa-info-circle mr-1.5"></i> Excusé
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                {{ $record->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                {{ $record->reason ?? '-' }}
                            </td>
                            @if(auth()->user()->role === 'enseignant')
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('enseignant.attendance.edit', $record) }}" class="rounded-lg bg-indigo-50 p-2 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('enseignant.attendance.destroy', $record) }}" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement?')" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-50 p-2 text-red-600 hover:bg-red-100 transition-colors" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'enseignant' ? '6' : '5' }}" class="px-6 py-12 text-center">
                                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900">Aucun enregistrement de présence</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    @if(auth()->user()->role === 'enseignant')
                                        Commencez par marquer les présences
                                    @else
                                        Les présences seront affichées ici une fois enregistrées
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attendance->hasPages())
            <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                {{ $attendance->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
