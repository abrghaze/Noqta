@extends('layouts.modern')

@section('title', 'Paramètres')
@section('breadcrumb', 'Paramètres')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-cog mr-3 text-indigo-600"></i>
            Paramètres
        </h1>
        <p class="text-gray-600 mt-2">Gérez vos informations personnelles et préférences</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 animate-slide-in">
            <div class="rounded-lg bg-green-50 p-4 border-l-4 border-green-400">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-auto">
                        <i class="fas fa-times text-green-400"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Account Settings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                        Informations du Compte
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom Complet</label>
                            <input type="text" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Adresse Email</label>
                            <input type="email" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                            <input type="text" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('phone') border-red-500 @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                   placeholder="+221 77 123 45 67">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="border-t border-gray-200 my-8"></div>

                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-indigo-600"></i>
                            Changer le Mot de Passe
                        </h3>

                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Mot de Passe Actuel</label>
                            <input type="password" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('current_password') border-red-500 @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Requis uniquement pour changer le mot de passe</p>
                        </div>

                        <div class="mb-6">
                            <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">Nouveau Mot de Passe</label>
                            <input type="password" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('new_password') border-red-500 @enderror" 
                                   id="new_password" name="new_password">
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères</p>
                        </div>

                        <div class="mb-6">
                            <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le Nouveau Mot de Passe</label>
                            <input type="password" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                   id="new_password_confirmation" name="new_password_confirmation">
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center">
                                <i class="fas fa-times mr-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-md flex items-center">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer les Modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Account Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-indigo-600"></i>
                        Informations du Compte
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Rôle</p>
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Membre depuis</p>
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Dernière modification</p>
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Version</p>
                            <p class="text-sm font-semibold text-gray-900">Noqta v1.0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bell mr-2 text-indigo-600"></i>
                        Préférences de Notifications
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">Notifications par email</span>
                            <input type="checkbox" checked disabled class="w-10 h-5 bg-indigo-600 rounded-full appearance-none cursor-not-allowed opacity-50">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">Alertes de notes</span>
                            <input type="checkbox" checked disabled class="w-10 h-5 bg-indigo-600 rounded-full appearance-none cursor-not-allowed opacity-50">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-700">Alertes d'absences</span>
                            <input type="checkbox" checked disabled class="w-10 h-5 bg-indigo-600 rounded-full appearance-none cursor-not-allowed opacity-50">
                        </label>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Configuration avancée bientôt disponible
                        </p>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-shield-alt mr-2 text-indigo-600"></i>
                        Sécurité
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <p class="text-sm text-gray-700 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Compte sécurisé
                        </p>
                        <p class="text-sm text-gray-700 flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            Email vérifié
                        </p>
                        <p class="text-sm text-gray-700 flex items-center">
                            <i class="fas fa-lock text-indigo-600 mr-2"></i>
                            Mot de passe fort recommandé
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
