@extends('layouts.modern')

@section('title', 'Gestion des Matières')
@section('breadcrumb', 'Matières')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Matières</h1>
            <p class="mt-2 text-sm text-gray-600">Gérer toutes les matières enseignées</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button onclick="window.location='{{ route('directeur.subjects.create') }}'" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-all">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle Matière
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('directeur.subjects.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Nom de la matière...">
                </div>
            </div>

            <!-- Class Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Classe</label>
                <select name="class_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrer
                </button>
                <a href="{{ route('directeur.subjects.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Subjects Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($subjects as $subject)
            <div class="group relative overflow-hidden rounded-xl bg-white shadow-sm hover:shadow-xl transition-all duration-300">
                <!-- Colored Header -->
                <div class="h-2 bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                
                <div class="p-6">
                    <!-- Subject Info -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $subject->name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 flex items-center">
                                <i class="fas fa-door-open mr-2"></i>
                                {{ $subject->class->name }}
                            </p>
                        </div>
                        <div class="rounded-full bg-blue-100 p-3">
                            <i class="fas fa-book text-xl text-blue-600"></i>
                        </div>
                    </div>

                    <!-- Teacher Info -->
                    <div class="mb-4 rounded-lg bg-gray-50 p-3">
                        @if($subject->teacher)
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($subject->teacher->name, 0, 2)) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500">Enseignant</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $subject->teacher->name }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center text-gray-400">
                                <i class="fas fa-user-slash mr-2"></i>
                                <span class="text-sm">Aucun enseignant assigné</span>
                            </div>
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="mb-4 grid grid-cols-2 gap-3">
                        <div class="rounded-lg border border-gray-200 p-3 text-center">
                            <p class="text-xs text-gray-500">Coefficient</p>
                            <p class="text-2xl font-bold text-indigo-600">{{ $subject->coefficient }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 p-3 text-center">
                            <p class="text-xs text-gray-500">Notes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $subject->grades->count() }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($subject->description)
                        <p class="mb-4 text-sm text-gray-600 line-clamp-2">
                            {{ $subject->description }}
                        </p>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('directeur.subjects.show', $subject) }}" 
                           class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            <i class="fas fa-eye mr-2"></i>
                            Détails
                        </a>
                        
                        <div class="flex items-center space-x-2">
                            <button onclick="window.location='{{ route('directeur.subjects.edit', $subject) }}'" 
                                    class="rounded-lg bg-gray-100 p-2 text-gray-600 hover:bg-gray-200 transition-colors" 
                                    title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('directeur.subjects.destroy', $subject) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette matière?')" 
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="rounded-lg bg-red-50 p-2 text-red-600 hover:bg-red-100 transition-colors" 
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="rounded-xl bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gray-100">
                        <i class="fas fa-book text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune matière trouvée</h3>
                    <p class="mt-2 text-sm text-gray-500">Commencez par créer une nouvelle matière</p>
                    <button onclick="window.location='{{ route('directeur.subjects.create') }}'" 
                            class="mt-6 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Créer une matière
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($subjects->hasPages())
        <div class="mt-8">
            {{ $subjects->links() }}
        </div>
    @endif
</div>
@endsection
