@extends('layouts.modern')

@section('title', 'Gestion des Classes')
@section('breadcrumb', 'Classes')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Classes</h1>
            <p class="mt-2 text-sm text-gray-600">Gérer toutes les classes de l'établissement</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button onclick="window.location='{{ route('directeur.classes.create') }}'" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-all">
                <i class="fas fa-plus mr-2"></i>
                Nouvelle Classe
            </button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('directeur.classes.index') }}" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Rechercher une classe...">
                </div>
            </div>
            <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition-colors">
                <i class="fas fa-filter mr-2"></i>
                Rechercher
            </button>
            <a href="{{ route('directeur.classes.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </form>
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($classes as $class)
            <div class="group relative overflow-hidden rounded-xl bg-white p-6 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 opacity-50"></div>
                
                <!-- Content -->
                <div class="relative">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $class->name }}
                            </h3>
                            @if($class->teacher)
                                <p class="mt-1 text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                                    {{ $class->teacher->name }}
                                </p>
                            @else
                                <p class="mt-1 text-sm text-gray-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    Aucun enseignant assigné
                                </p>
                            @endif
                        </div>
                        <div class="rounded-full bg-indigo-100 p-3">
                            <i class="fas fa-door-open text-xl text-indigo-600"></i>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div class="rounded-lg bg-white p-3 shadow-sm">
                            <div class="flex items-center">
                                <div class="rounded-full bg-purple-100 p-2">
                                    <i class="fas fa-users text-purple-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500">Étudiants</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $class->students_count }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="rounded-lg bg-white p-3 shadow-sm">
                            <div class="flex items-center">
                                <div class="rounded-full bg-blue-100 p-2">
                                    <i class="fas fa-book text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs text-gray-500">Matières</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $class->subjects->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($class->description)
                        <p class="mb-4 text-sm text-gray-600 line-clamp-2">
                            {{ $class->description }}
                        </p>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('directeur.classes.show', $class) }}" 
                           class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            <i class="fas fa-eye mr-2"></i>
                            Voir détails
                        </a>
                        
                        <div class="flex items-center space-x-2">
                            <button onclick="window.location='{{ route('directeur.classes.edit', $class) }}'" 
                                    class="rounded-lg bg-gray-100 p-2 text-gray-600 hover:bg-gray-200 transition-colors" 
                                    title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('directeur.classes.destroy', $class) }}" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe?')" 
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

                <!-- Hover Effect -->
                <div class="absolute inset-0 border-2 border-transparent group-hover:border-indigo-500 rounded-xl transition-all duration-300"></div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="rounded-xl bg-white p-12 text-center shadow-sm">
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gray-100">
                        <i class="fas fa-door-open text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune classe trouvée</h3>
                    <p class="mt-2 text-sm text-gray-500">Commencez par créer une nouvelle classe</p>
                    <button onclick="window.location='{{ route('directeur.classes.create') }}'" 
                            class="mt-6 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Créer une classe
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($classes->hasPages())
        <div class="mt-8">
            {{ $classes->links() }}
        </div>
    @endif
</div>
@endsection
