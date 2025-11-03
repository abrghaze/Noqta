@extends('layouts.modern')

@section('title', 'Mes Matières')
@section('breadcrumb', 'Mes Matières')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes Matières</h1>
        <p class="mt-2 text-sm text-gray-600">Matières que j'enseigne</p>
    </div>

    <!-- Subjects Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($subjects as $subject)
            <a href="{{ route('enseignant.subjects.show', $subject) }}" class="group block">
                <div class="rounded-xl bg-white p-6 shadow-sm hover:shadow-xl transition-all duration-300">
                    <!-- Subject Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="rounded-full bg-green-100 p-3">
                            <i class="fas fa-book text-2xl text-green-600"></i>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800">
                            Coef: {{ $subject->coefficient }}
                        </span>
                    </div>

                    <!-- Subject Name -->
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors mb-2">
                        {{ $subject->name }}
                    </h3>

                    <!-- Subject Info -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-door-open w-5 text-purple-600"></i>
                            <span class="ml-2">{{ $subject->class->name }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user-graduate w-5 text-blue-600"></i>
                            <span class="ml-2">{{ $subject->class->students->count() }} étudiants</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clipboard-list w-5 text-indigo-600"></i>
                            <span class="ml-2">
                                @php
                                    $gradesCount = \App\Models\Grade::where('subject_id', $subject->id)->count();
                                @endphp
                                {{ $gradesCount }} notes
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-green-600 group-hover:text-green-500">Voir détails</span>
                            <i class="fas fa-arrow-right text-green-600 group-hover:text-green-500"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full">
                <div class="rounded-xl bg-white p-12 text-center shadow-sm">
                    <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900">Aucune matière assignée</h3>
                    <p class="mt-2 text-sm text-gray-500">Vous n'avez pas encore de matières assignées</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
