@extends('layouts.modern')

@section('title', 'Mes Étudiants')
@section('breadcrumb', 'Mes Étudiants')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes Étudiants</h1>
        <p class="mt-2 text-sm text-gray-600">Liste de tous les étudiants dans mes classes</p>
    </div>

    <!-- Search Bar -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('enseignant.students.index') }}" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Rechercher un étudiant...">
                </div>
            </div>
            <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition-colors">
                <i class="fas fa-search mr-2"></i>
                Rechercher
            </button>
            <a href="{{ route('enseignant.students.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </form>
    </div>

    <!-- Students Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($students as $student)
            <a href="{{ route('enseignant.students.show', $student) }}" class="group block">
                <div class="rounded-xl bg-white p-6 shadow-sm hover:shadow-xl transition-all duration-300">
                    <!-- Student Avatar -->
                    <div class="flex items-center mb-4">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($student->user->name, 0, 2)) }}
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $student->user->name }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ $student->matricule }}</p>
                        </div>
                    </div>

                    <!-- Student Info -->
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-door-open w-5 text-purple-600"></i>
                            <span class="ml-2">{{ $student->class->name }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope w-5 text-blue-600"></i>
                            <span class="ml-2">{{ $student->user->email }}</span>
                        </div>
                        @if($student->phone)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone w-5 text-green-600"></i>
                                <span class="ml-2">{{ $student->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-lg bg-green-50 p-3 text-center">
                            <p class="text-xs text-green-600">Notes</p>
                            <p class="text-lg font-bold text-green-700">{{ $student->grades->count() }}</p>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-3 text-center">
                            <p class="text-xs text-blue-600">Présences</p>
                            <p class="text-lg font-bold text-blue-700">
                                {{ $student->attendance->where('status', 'present')->count() }}/{{ $student->attendance->count() }}
                            </p>
                        </div>
                    </div>

                    <!-- View Button -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm font-medium text-indigo-600 group-hover:text-indigo-500">
                            <span>Voir le profil</span>
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full">
                <div class="rounded-xl bg-white p-12 text-center shadow-sm">
                    <i class="fas fa-user-graduate text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">Aucun étudiant trouvé</h3>
                    <p class="mt-2 text-sm text-gray-500">Aucun étudiant ne correspond à votre recherche</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($students->hasPages())
        <div class="mt-8">
            {{ $students->links() }}
        </div>
    @endif
</div>
@endsection
