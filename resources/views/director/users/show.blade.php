@extends('layouts.modern')

@section('title', 'Détails de l\'Utilisateur')
@section('breadcrumb', 'Détails de l\'Utilisateur')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Détails de l'Utilisateur</h1>
                <p class="mt-2 text-sm text-gray-600">Informations complètes sur {{ $user->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('directeur.users.edit', $user) }}" 
                   class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('directeur.users.index') }}" 
                   class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left Column - User Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="rounded-xl bg-white shadow-sm p-6">
                <div class="flex flex-col items-center">
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 flex items-center justify-center text-white text-3xl font-bold mb-4">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                    
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
                    
                    <span class="mt-3 inline-flex items-center rounded-full px-4 py-1.5 text-sm font-semibold {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                        <i class="fas {{ $roleIcons[$user->role] ?? 'fa-user' }} mr-2"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6 space-y-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-envelope text-gray-400 w-5"></i>
                        <span class="ml-3 text-gray-900">{{ $user->email }}</span>
                    </div>
                    @if($user->phone)
                        <div class="flex items-center text-sm">
                            <i class="fas fa-phone text-gray-400 w-5"></i>
                            <span class="ml-3 text-gray-900">{{ $user->phone }}</span>
                        </div>
                    @endif
                    <div class="flex items-center text-sm">
                        <i class="fas fa-calendar text-gray-400 w-5"></i>
                        <span class="ml-3 text-gray-900">Inscrit le {{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-circle text-green-500 w-5"></i>
                        <span class="ml-3 text-gray-900">Compte actif</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="rounded-xl bg-white shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
                <div class="space-y-2">
                    <a href="{{ route('directeur.users.edit', $user) }}" 
                       class="flex items-center w-full rounded-lg border border-gray-200 p-3 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-edit text-indigo-600 w-5"></i>
                        <span class="ml-3 text-sm font-medium text-gray-900">Modifier le profil</span>
                    </a>
                    <form method="POST" action="{{ route('directeur.users.destroy', $user) }}" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center w-full rounded-lg border border-gray-200 p-3 hover:bg-red-50 transition-colors">
                            <i class="fas fa-trash text-red-600 w-5"></i>
                            <span class="ml-3 text-sm font-medium text-gray-900">Supprimer le compte</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Detailed Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Information -->
            @if($user->role === 'etudiant' && $user->student)
                <div class="rounded-xl bg-white shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-user-graduate text-purple-600 mr-2"></i>
                        Informations Étudiant
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Matricule</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->student->matricule }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Classe</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->student->class->name ?? 'Non assigné' }}</p>
                        </div>
                        @if($user->student->date_of_birth)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date de naissance</label>
                                <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->student->date_of_birth)->format('d/m/Y') }}</p>
                            </div>
                        @endif
                        @if($user->student->parent_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Parent</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->student->parent->user->name ?? 'N/A' }}</p>
                            </div>
                        @endif
                    </div>
                    
                    @if($user->student->address)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-500">Adresse</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->student->address }}</p>
                        </div>
                    @endif

                    <!-- Student Statistics -->
                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="rounded-lg bg-purple-50 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-purple-600 font-medium">Notes</p>
                                    <p class="mt-1 text-2xl font-bold text-purple-900">{{ $user->student->grades->count() }}</p>
                                </div>
                                <i class="fas fa-clipboard-list text-2xl text-purple-400"></i>
                            </div>
                        </div>
                        <div class="rounded-lg bg-green-50 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-600 font-medium">Moyenne</p>
                                    <p class="mt-1 text-2xl font-bold text-green-900">
                                        {{ $user->student->grades->count() > 0 ? number_format($user->student->grades->avg('grade_value'), 2) : 'N/A' }}
                                    </p>
                                </div>
                                <i class="fas fa-chart-line text-2xl text-green-400"></i>
                            </div>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">Présence</p>
                                    @php
                                        $totalAttendance = $user->student->attendance->count();
                                        $presentCount = $user->student->attendance->where('status', 'present')->count();
                                        $attendanceRate = $totalAttendance > 0 ? ($presentCount / $totalAttendance) * 100 : 0;
                                    @endphp
                                    <p class="mt-1 text-2xl font-bold text-blue-900">{{ number_format($attendanceRate, 0) }}%</p>
                                </div>
                                <i class="fas fa-calendar-check text-2xl text-blue-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Grades -->
                @if($user->student->grades->count() > 0)
                    <div class="rounded-xl bg-white shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>
                            Notes Récentes
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Matière</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Note</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($user->student->grades->sortByDesc('date')->take(5) as $grade)
                                        <tr>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">{{ $grade->subject->name }}</td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $grade->grade_value >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $grade->grade_value }}/{{ $grade->max_grade }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">{{ $grade->exam_type }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">{{ $grade->date->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif

            <!-- Parent Information -->
            @if($user->role === 'parent' && $user->parentProfile)
                <div class="rounded-xl bg-white shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Informations Parent
                    </h3>
                    
                    @if($user->parentProfile->relationship)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500">Relation</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->parentProfile->relationship }}</p>
                        </div>
                    @endif

                    @if($user->parentProfile->students && $user->parentProfile->students->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Enfants</label>
                            <div class="space-y-2">
                                @foreach($user->parentProfile->students as $student)
                                    <div class="flex items-center rounded-lg border border-gray-200 p-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-indigo-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $student->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $student->class->name ?? 'N/A' }} - {{ $student->matricule }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">Aucun enfant lié à ce compte</p>
                    @endif
                </div>
            @endif

            <!-- Teacher Information -->
            @if($user->role === 'enseignant')
                <div class="rounded-xl bg-white shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-chalkboard-teacher text-green-600 mr-2"></i>
                        Informations Enseignant
                    </h3>
                    
                    @php
                        $classes = \App\Models\ClassRoom::where('teacher_id', $user->id)->get();
                        $subjects = \App\Models\Subject::where('teacher_id', $user->id)->get();
                    @endphp

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-6">
                        <div class="rounded-lg bg-green-50 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-600 font-medium">Classes</p>
                                    <p class="mt-1 text-2xl font-bold text-green-900">{{ $classes->count() }}</p>
                                </div>
                                <i class="fas fa-door-open text-2xl text-green-400"></i>
                            </div>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">Matières</p>
                                    <p class="mt-1 text-2xl font-bold text-blue-900">{{ $subjects->count() }}</p>
                                </div>
                                <i class="fas fa-book text-2xl text-blue-400"></i>
                            </div>
                        </div>
                    </div>

                    @if($classes->count() > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Classes Assignées</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($classes as $class)
                                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                                        <i class="fas fa-door-open mr-2 text-gray-500"></i>
                                        {{ $class->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($subjects->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Matières Enseignées</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($subjects as $subject)
                                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                                        <i class="fas fa-book mr-2 text-gray-500"></i>
                                        {{ $subject->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Activity Timeline -->
            <div class="rounded-xl bg-white shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-history text-gray-600 mr-2"></i>
                    Activité Récente
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-user-plus text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Compte créé</p>
                            <p class="text-xs text-gray-500">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @if($user->updated_at != $user->created_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-edit text-blue-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Dernière modification</p>
                                <p class="text-xs text-gray-500">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
