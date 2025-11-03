@extends('layouts.modern')

@section('title', 'Gestion des Notes')
@section('breadcrumb', 'Notes')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                @if(auth()->user()->role === 'enseignant')
                    Mes Notes
                @elseif(auth()->user()->role === 'etudiant')
                    Mes Notes
                @elseif(auth()->user()->role === 'parent')
                    Notes de {{ auth()->user()->parentProfile->student->user->name }}
                @else
                    Toutes les Notes
                @endif
            </h1>
            <p class="mt-2 text-sm text-gray-600">Consulter et gérer les notes</p>
        </div>
        @if(auth()->user()->role === 'enseignant')
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('enseignant.grades.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter une Note
                </a>
            </div>
        @endif
    </div>

    <!-- Stats Cards (for students/parents) -->
    @if(in_array(auth()->user()->role, ['etudiant', 'parent']))
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
            <div class="rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Moyenne Générale</p>
                        <p class="mt-2 text-4xl font-bold">{{ number_format($grades->avg('grade_value'), 2) }}/20</p>
                    </div>
                    <i class="fas fa-chart-line text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="rounded-xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Notes au-dessus de 10</p>
                        <p class="mt-2 text-4xl font-bold">{{ $grades->where('grade_value', '>=', 10)->count() }}</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="rounded-xl bg-gradient-to-br from-blue-500 to-cyan-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Notes</p>
                        <p class="mt-2 text-4xl font-bold">{{ $grades->count() }}</p>
                    </div>
                    <i class="fas fa-clipboard-list text-4xl opacity-50"></i>
                </div>
            </div>
        </div>
    @endif

    <!-- Grades Table -->
    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @if(auth()->user()->role === 'enseignant')
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Étudiant</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Commentaire</th>
                        @if(auth()->user()->role === 'enseignant')
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($grades as $grade)
                        <tr class="hover:bg-gray-50 transition-colors">
                            @if(auth()->user()->role === 'enseignant')
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($grade->student->user->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $grade->student->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $grade->student->class->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $grade->subject->name }}</div>
                                <div class="text-sm text-gray-500">Coef: {{ $grade->subject->coefficient }}</div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $grade->grade_value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                    </span>
                                    <span class="ml-2 text-xs text-gray-500">
                                        ({{ number_format(($grade->grade_value / $grade->max_grade) * 100, 1) }}%)
                                    </span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-lg bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                    {{ $grade->exam_type }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $grade->date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                {{ $grade->comment ?? '-' }}
                            </td>
                            @if(auth()->user()->role === 'enseignant')
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('enseignant.grades.edit', $grade) }}" class="rounded-lg bg-indigo-50 p-2 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('enseignant.grades.destroy', $grade) }}" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette note?')" 
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
                            <td colspan="{{ auth()->user()->role === 'enseignant' ? '7' : '6' }}" class="px-6 py-12 text-center">
                                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900">Aucune note disponible</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    @if(auth()->user()->role === 'enseignant')
                                        Commencez par ajouter des notes
                                    @else
                                        Les notes seront affichées ici une fois saisies
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($grades->hasPages())
            <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                {{ $grades->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
