@extends('layouts.modern')

@section('title', 'Gestion des Utilisateurs')
@section('breadcrumb', 'Utilisateurs')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Utilisateurs</h1>
            <p class="mt-2 text-sm text-gray-600">Gérer tous les utilisateurs du système</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button onclick="window.location='{{ route('directeur.users.create') }}'" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-all">
                <i class="fas fa-plus mr-2"></i>
                Nouvel Utilisateur
            </button>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('directeur.users.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <!-- Search -->
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Nom ou email...">
                </div>
            </div>

            <!-- Role Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                <select name="role" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Tous les rôles</option>
                    <option value="etudiant" {{ request('role') == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                    <option value="enseignant" {{ request('role') == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                    <option value="parent" {{ request('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                    <option value="directeur" {{ request('role') == 'directeur' ? 'selected' : '' }}>Directeur</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrer
                </button>
                <a href="{{ route('directeur.users.index') }}" class="rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-colors">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
        <div class="rounded-lg bg-gradient-to-br from-purple-500 to-purple-700 p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Étudiants</p>
                    <p class="text-2xl font-bold">{{ $users->where('role', 'etudiant')->count() }}</p>
                </div>
                <i class="fas fa-user-graduate text-3xl opacity-50"></i>
            </div>
        </div>
        
        <div class="rounded-lg bg-gradient-to-br from-green-500 to-green-700 p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Enseignants</p>
                    <p class="text-2xl font-bold">{{ $users->where('role', 'enseignant')->count() }}</p>
                </div>
                <i class="fas fa-chalkboard-teacher text-3xl opacity-50"></i>
            </div>
        </div>
        
        <div class="rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Parents</p>
                    <p class="text-2xl font-bold">{{ $users->where('role', 'parent')->count() }}</p>
                </div>
                <i class="fas fa-users text-3xl opacity-50"></i>
            </div>
        </div>
        
        <div class="rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-700 p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total</p>
                    <p class="text-2xl font-bold">{{ $users->total() }}</p>
                </div>
                <i class="fas fa-user-friends text-3xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Rôle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date d'inscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $roleColors = [
                                        'etudiant' => 'bg-purple-100 text-purple-800',
                                        'enseignant' => 'bg-green-100 text-green-800',
                                        'parent' => 'bg-blue-100 text-blue-800',
                                        'directeur' => 'bg-red-100 text-red-800',
                                    ];
                                    $roleIcons = [
                                        'etudiant' => 'fa-user-graduate',
                                        'enseignant' => 'fa-chalkboard-teacher',
                                        'parent' => 'fa-users',
                                        'directeur' => 'fa-user-tie',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas {{ $roleIcons[$user->role] ?? 'fa-user' }} mr-1.5"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                    {{ $user->email }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                    <i class="fas fa-circle mr-1.5 text-xs"></i>
                                    Actif
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('directeur.users.show', $user) }}" class="rounded-lg bg-indigo-50 p-2 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('directeur.users.edit', $user) }}" class="rounded-lg bg-gray-50 p-2 text-gray-600 hover:bg-gray-100 transition-colors" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('directeur.users.destroy', $user) }}" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-50 p-2 text-red-600 hover:bg-red-100 transition-colors" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-900">Aucun utilisateur trouvé</p>
                                    <p class="text-sm text-gray-500 mt-1">Commencez par ajouter un nouvel utilisateur</p>
                                    <button onclick="window.location='{{ route('directeur.users.create') }}'" 
                                            class="mt-4 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Ajouter un utilisateur
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex flex-1 justify-between sm:hidden">
                        @if ($users->onFirstPage())
                            <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-300">Précédent</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Précédent</a>
                        @endif

                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Suivant</a>
                        @else
                            <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-300">Suivant</span>
                        @endif
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de
                                <span class="font-medium">{{ $users->firstItem() }}</span>
                                à
                                <span class="font-medium">{{ $users->lastItem() }}</span>
                                sur
                                <span class="font-medium">{{ $users->total() }}</span>
                                résultats
                            </p>
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
